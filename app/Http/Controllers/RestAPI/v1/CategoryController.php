<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Utils\CategoryManager;
use App\Utils\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function get_categories(Request $request): JsonResponse
    {
        $categoriesID = [];
        $shop = $request->has('shop_slug') && empty($request['shop_slug']) ? Shop::where('slug', $request['shop_slug'])->first() : null;

        if ($shop) {
            $categoriesID = Product::active()
                ->when($shop['author_type'] == 'admin', function ($query) use ($request) {
                    return $query->where(['added_by' => 'admin']);
                })
                ->when($shop['author_type'] != 'admin', function ($query) use ($request, $shop) {
                    return $query->where(['added_by' => 'seller'])->where('user_id', $shop['seller_id']);
                })->pluck('category_id');
        }

        $categories = Category::when($shop, function ($query) use ($categoriesID) {
                return $query->whereIn('id', $categoriesID);
            })
            ->with(['product' => function ($query) {
                return $query->active()->withCount(['orderDetails'])->latest()->limit(10);
            }])
            ->withCount(['product' => function ($query) use ($request, $shop) {
                return $query->active()->when($shop && $shop['author_type'] != 'admin', function ($query) use ($request, $shop) {
                    return $query->where(['added_by' => 'seller', 'user_id' => $shop['seller_id'], 'status' => '1']);
                });
            }])->with(['childes' => function ($query) {
                return $query->with(['childes' => function ($query) {
                    return $query->withCount(['subSubCategoryProduct' => function ($query) {
                        return $query->active();
                    }])->where('position', 2);
                }])->withCount(['subCategoryProduct' => function ($query) {
                    return $query->active();
                }])->where('position', 1);
            }, 'childes.childes'])
            ->where(['position' => 0])->get();

        $categories = CategoryManager::getPriorityWiseCategorySortQuery(query: $categories);

        return response()->json($categories->values());
    }

    public function get_products(Request $request, $id): JsonResponse
    {
        $dataLimit = $request['limit'] ?? 'all';
        $products = CategoryManager::products($id, $request, $dataLimit);
        $productFinal = Helpers::product_data_formatting($products, true);

        if ($dataLimit == 'all') {
            return response()->json($productFinal, 200);
        }

        return response()->json([
            'total_size' => $products->total(),
            'limit' => (int)$request['limit'],
            'offset' => (int)$request['offset'],
            'products' => $productFinal,
        ], 200);
    }

    public function find_what_you_need():JsonResponse
    {
        $find_what_you_need_categories = Category::where('parent_id', 0)
            ->with(['childes' => function ($query) {
                $query->withCount(['subCategoryProduct' => function ($query) {
                    return $query->active();
                }]);
            }])
            ->withCount(['product' => function ($query) {
                return $query->active();
            }])
            ->get()->toArray();

        $getCategories = [];
        foreach ($find_what_you_need_categories as $category) {
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $getCategories[] = $category;
        }

        $final_category = [];
        foreach ($getCategories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }

        return response()->json(['find_what_you_need' => $final_category], 200);
    }

}
