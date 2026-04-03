<?php

namespace App\Http\Controllers\Admin\Promotion;

use App\Contracts\Repositories\BannerRepositoryInterface;
use App\Contracts\Repositories\BrandRepositoryInterface;
use App\Contracts\Repositories\CategoryRepositoryInterface;
use App\Contracts\Repositories\ProductRepositoryInterface;
use App\Contracts\Repositories\ShopRepositoryInterface;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\BannerAddRequest;
use App\Http\Requests\Admin\BannerUpdateRequest;
use App\Services\BannerService;
use App\Traits\FileManagerTrait;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BannerController extends BaseController
{
    use FileManagerTrait {
        delete as deleteFile;
        update as updateFile;
    }

    public function __construct(
        private readonly BannerRepositoryInterface   $bannerRepo,
        private readonly CategoryRepositoryInterface $categoryRepo,
        private readonly ShopRepositoryInterface     $shopRepo,
        private readonly BrandRepositoryInterface    $brandRepo,
        private readonly ProductRepositoryInterface  $productRepo,
        private readonly BannerService               $bannerService,
    )
    {
    }

    /**
     * @param Request|null $request
     * @param string|null $type
     * @return View Index function is the starting point of a controller
     * Index function is the starting point of a controller
     */
    public function index(Request|null $request, ?string $type = null): View
    {
        $bannerTypes = $this->bannerService->getBannerTypes();
        $banners = $this->bannerRepo->getListWhereIn(
            orderBy: ['id' => 'desc'],
            searchValue: $request['searchValue'],
            filters: ['theme' => theme_root_path()],
            whereInFilters: ['banner_type' => array_keys($bannerTypes)],
            dataLimit: getWebConfig(name: 'pagination_limit'),
        );
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $shops = $this->shopRepo->getListWithScope(scope: 'active', filters: ['author_type' => 'vendor'],  dataLimit: 'all');
        $inhouseShop = getInHouseShopConfig();
        $shops = $shops->prepend($inhouseShop);
        $brands = $this->brandRepo->getListWhere(dataLimit: 'all');
        $products = $this->productRepo->getListWithScope(scope: 'active', dataLimit: 'all');
        return view('admin-views.banner.view', compact('banners', 'categories', 'shops', 'brands', 'products', 'bannerTypes'));
    }

    public function add(BannerAddRequest $request): RedirectResponse
    {
        $bannerUrl = $this->getBannerUrl(request: $request);
        $data = $this->bannerService->getProcessedData(request: $request, bannerUrl: $bannerUrl);
        $this->bannerRepo->add(data: $data);
        ToastMagic::success(translate('banner_added_successfully'));
        return redirect()->route('admin.banner.list');
    }

    public function getBannerUrl($request): string
    {
        if ($request['resource_type'] == 'product') {
            $product = $this->productRepo->getFirstWhere(params: ['id' => $request['product_id']]);
            $bannerUrl = route('product', ['slug' => $product['slug']]);
        } else if ($request['resource_type'] == 'category') {
            $category = $this->categoryRepo->getFirstWhere(params: ['id' => $request['category_id']]);
            $bannerUrl = route('products', ['category_id' => $category['id'], 'data_from' => 'category', 'page' => 1]);
        } else if ($request['resource_type'] == 'shop') {
            $shop = $this->shopRepo->getFirstWhere(params: ['id' => $request['shop_id']]);
            $bannerUrl = route('vendor-shop', ['slug' => $shop['slug']]);
        } else if ($request['resource_type'] == 'brand') {
            $brand = $this->brandRepo->getFirstWhere(params: ['id' => $request['brand_id']]);
            $bannerUrl = route('products', ['brand_id' => $brand['id'], 'data_from' => 'brand', 'page' => 1]);
        } else {
            $bannerUrl = $request['url'];
        }

        return $bannerUrl;
    }

    public function getUpdateView($id): View
    {
        $bannerTypes = $this->bannerService->getBannerTypes();
        $banner = $this->bannerRepo->getFirstWhere(params: ['id' => $id]);
        $categories = $this->categoryRepo->getListWhere(filters: ['position' => 0], dataLimit: 'all');
        $shops = $this->shopRepo->getListWithScope(scope: 'active', filters: ['author_type' => 'vendor'] , dataLimit: 'all');
        $inhouseShop = getInHouseShopConfig();
        $shops = $shops->prepend($inhouseShop);
        $brands = $this->brandRepo->getListWhere(dataLimit: 'all');
        $products = $this->productRepo->getListWithScope(scope: 'active', dataLimit: 'all');
        return view('admin-views.banner.edit', compact('banner', 'categories', 'shops', 'brands', 'products', 'bannerTypes'));
    }

    public function update(BannerUpdateRequest $request, $id): RedirectResponse
    {
        $bannerUrl = $this->getBannerUrl(request: $request);
        $banner = $this->bannerRepo->getFirstWhere(params: ['id' => $id]);
        $data = $this->bannerService->getProcessedData(request: $request, bannerUrl: $bannerUrl, image: $banner['photo']);
        $this->bannerRepo->update(id: $banner['id'], data: $data);
        ToastMagic::success(translate('banner_updated_successfully'));
        return redirect()->route('admin.banner.list');
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $bannerType = $this->bannerRepo->getListWhere(searchValue: "Popup Banner", dataLimit: 'all');
        foreach ($bannerType as $item) {
            $this->bannerRepo->update(id: $item['id'], data: ['published' => 0]);
        }
        $status = $request->get('status', 0);
        $this->bannerRepo->update(id: $request['id'], data: ['published' => $status]);
        return response()->json([
            'message' => $status == 1 ? translate("banner_published_successfully") : translate("banner_unpublished_successfully"),
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $banner = $this->bannerRepo->getFirstWhere(params: ['id' => $request['id']]);
        $this->deleteFile(filePath: '/banner/' . $banner['photo']);
        $this->bannerRepo->delete(params: ['id' => $request['id']]);
        return response()->json(['message' => translate('banner_deleted_successfully')]);
    }
}
