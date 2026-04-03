<?php

namespace App\Utils;

use App\Models\FlashDeal;
use App\Models\FlashDealProduct;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CategoryManager
{
    public static function parents(): Collection|array
    {
        return Category::with(['childes.childes'])->where('position', 0)->orderBy('priority', 'desc')->get();
    }

    public static function child($parent_id)
    {
        return Category::where(['parent_id' => $parent_id])->get();
    }

    public static function products($category_id, $request = null, $dataLimit = null)
    {
        $user = Helpers::getCustomerInformation($request);
        $id = '"' . $category_id . '"';
        $products = Product::with(['flashDealProducts.flashDeal', 'rating', 'seller.shop', 'tags', 'clearanceSale' => function ($query) {
                return $query->active();
            }])
            ->withCount(['reviews', 'wishList' => function ($query) use ($user) {
                $query->where('customer_id', $user != 'offline' ? $user['id'] : '0');
            }])
            ->active()
            ->where('category_ids', 'like', "%{$id}%")
            ->when($request->has('search') && !empty($request['search']), function ($query) use ($request) {
                $searchKey = $request['search'];
                $productsIDArray = [];
                $searchProducts = ProductManager::search_products($request, $searchKey);
                if ($searchProducts['products'] == null || getDefaultLanguage() != 'en') {
                    $searchProducts = ProductManager::translated_product_search(base64_encode($searchKey));
                }
                if ($searchProducts['products']) {
                    foreach ($searchProducts['products'] as $product) {
                        $productsIDArray[] = $product->id;
                    }
                }

                $searchName = str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', preg_replace('/\s\s+/', ' ', $searchKey));
                return $query->when(!empty($productsIDArray), function ($query) use ($productsIDArray) {
                    return $query->whereIn('id', $productsIDArray);
                })->when(empty($productsIDArray), function ($query) use ($productsIDArray) {
                    return $query->whereIn('id', [0]);
                })->orderByRaw("CASE WHEN name LIKE '%{$searchName}%' THEN 1 ELSE 2 END, LOCATE('{$searchName}', name), name");
            });

        $products = ProductManager::getPriorityWiseCategoryWiseProductsQuery(query: $products, dataLimit: $dataLimit ?? 'all', offset: $request['offset'] ?? 1);

        $currentDate = date('Y-m-d H:i:s');
        $products?->map(function ($product) use ($currentDate) {
            $flashDealStatus = 0;
            $flashDealEndDate = 0;
            if (count($product->flashDealProducts) > 0) {
                $flashDeal = null;
                foreach ($product->flashDealProducts as $flashDealData) {
                    if ($flashDealData->flashDeal) {
                        $flashDeal = $flashDealData->flashDeal;
                    }
                }
                if ($flashDeal) {
                    $startDate = date('Y-m-d H:i:s', strtotime($flashDeal->start_date));
                    $endDate = date('Y-m-d H:i:s', strtotime($flashDeal->end_date));
                    $flashDealStatus = $flashDeal->status == 1 && (($currentDate >= $startDate) && ($currentDate <= $endDate)) ? 1 : 0;
                    $flashDealEndDate = $flashDeal->end_date;
                }
            }
            $product['flash_deal_status'] = $flashDealStatus;
            $product['flash_deal_end_date'] = $flashDealEndDate;
            return $product;
        });

        return $products;
    }

    public static function getCategoriesWithCountingAndPriorityWiseSorting($dataLimit = null, $dataForm = null)
    {
        $segment = str_replace('-', '_', Str::slug(request()->segment(1)));
        $cacheKey = 'cache_main_categories_list_' . (getDefaultLanguage() ?? 'en') . '_' . (request('offer_type') ?? 'default'). '_' . ($dataForm ?? 'default').(request('data_from', 'default')). $segment;
        $cacheKeys = Cache::get(CACHE_CONTAINER_FOR_LANGUAGE_WISE_CACHE_KEYS, []);

        if (!in_array($cacheKey, $cacheKeys)) {
            $cacheKeys[] = $cacheKey;
            Cache::put(CACHE_CONTAINER_FOR_LANGUAGE_WISE_CACHE_KEYS, $cacheKeys, CACHE_FOR_3_HOURS);
        }

        $featuredDealProducts = [];
        if (request('offer_type') == 'featured_deal') {
            $featuredDealID = FlashDeal::where(['deal_type' => 'feature_deal', 'status' => 1])->whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))->pluck('id')->first();
            $featuredDealProductIDs = $featuredDealID ? FlashDealProduct::where('flash_deal_id', $featuredDealID)->pluck('product_id')->toArray() : [];
            $featuredDealProducts = Product::whereIn('id', $featuredDealProductIDs)->get();
        }


        $categories = Cache::remember($cacheKey, CACHE_FOR_3_HOURS, function () use ($dataForm, $featuredDealProducts) {
                return Category::with(['product' => function ($query) {
                    return $query->active()->withCount(['orderDetails'])->with(['clearanceSale' => function ($query) {
                        return $query->active();
                    }]);
                }])
                ->when($dataForm == 'flash-deals', function ($query) {
                    return $query->whereHas('product.flashDealProducts.flashDeal');
                })
                ->withCount(['product' => function ($query) use ($dataForm, $featuredDealProducts) {
                    return $query->active()->when(request('offer_type') == 'clearance_sale', function ($query) {
                        return $query->whereHas('clearanceSale', function ($query) {
                            return $query->active();
                        });
                    })
                    ->when(request('offer_type') == 'discounted', function ($query) {
                        return $query->where('discount', '>', 0);
                    })
                    ->when(request('data_from') == 'publishing_house', function ($query) {
                        return $query->where(['product_type' => 'digital']);
                    })
                    ->when(request('offer_type') == 'featured_deal', function ($query) use ($featuredDealProducts) {
                        return $query->whereIn('id', $featuredDealProducts?->pluck('id')?->toArray() ?? [0]);
                    })
                    ->when($dataForm == 'flash-deals', function ($query) {
                        return $query->whereHas('flashDealProducts.flashDeal');
                    });
                }])
                ->with(['childes' => function ($query) use ($dataForm, $featuredDealProducts) {
                    return $query->with(['childes' => function ($query) use ($dataForm, $featuredDealProducts) {
                        return $query->withCount(['subSubCategoryProduct' => function ($query) use ($featuredDealProducts) {
                            return $query->active()->when(request('offer_type') == 'clearance_sale', function ($query) {
                                return $query->whereHas('clearanceSale', function ($query) {
                                    return $query->active();
                                });
                            })
                            ->when(request('offer_type') == 'discounted', function ($query) {
                                return $query->where('discount', '>', 0);
                            })
                            ->when(request('data_from') == 'publishing_house', function ($query) {
                                return $query->where(['product_type' => 'digital']);
                            })
                            ->when(request('offer_type') == 'featured_deal', function ($query) use ($featuredDealProducts) {
                                return $query->whereIn('id', $featuredDealProducts?->pluck('id')?->toArray() ?? [0]);
                            });
                        }])->where('position', 2);
                    }])->withCount(['subCategoryProduct' => function ($query) use ($dataForm, $featuredDealProducts) {
                        return $query->active()->when(request('offer_type') == 'clearance_sale', function ($query) {
                            return $query->whereHas('clearanceSale', function ($query) {
                                return $query->active();
                            });
                        })
                        ->when(request('offer_type') == 'discounted', function ($query) {
                            return $query->where('discount', '>', 0);
                        })
                        ->when(request('data_from') == 'publishing_house', function ($query) {
                            return $query->where(['product_type' => 'digital']);
                        })
                        ->when(request('offer_type') == 'featured_deal', function ($query) use ($featuredDealProducts) {
                            return $query->whereIn('id', $featuredDealProducts?->pluck('id')?->toArray() ?? [0]);
                        })
                        ->when($dataForm == 'flash-deals', function ($query) {
                            return $query->whereHas('flashDealProducts.flashDeal');
                        });
                    }])
                    ->where('position', 1);
                }, 'childes.childes'])->where('position', 0)->get();
        });

        $categoriesProcessed = self::getPriorityWiseCategorySortQuery(query: $categories);
        if ($dataLimit) {
            $categoriesProcessed = $categoriesProcessed->paginate($dataLimit);
        }
        return $categoriesProcessed;
    }

    public static function getPriorityWiseCategorySortQuery($query)
    {
        $categoryProductSortBy = getWebConfig(name: 'category_list_priority');
        if ($categoryProductSortBy && ($categoryProductSortBy['custom_sorting_status'] == 1)) {
            if ($categoryProductSortBy['sort_by'] == 'most_order') {
                return $query->map(function ($category) {
                    $category->order_count = $category?->product?->sum('order_details_count') ?? 0;
                    return $category;
                })->sortByDesc('order_count');
            } elseif ($categoryProductSortBy['sort_by'] == 'latest_created') {
                return $query->sortByDesc('id');
            } elseif ($categoryProductSortBy['sort_by'] == 'first_created') {
                return $query->sortBy('id');
            } elseif ($categoryProductSortBy['sort_by'] == 'a_to_z') {
                return $query->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
            } elseif ($categoryProductSortBy['sort_by'] == 'z_to_a') {
                return $query->sortByDesc('name', SORT_NATURAL | SORT_FLAG_CASE);
            }
        }
        return $query->sortByDesc('priority');
    }
}
