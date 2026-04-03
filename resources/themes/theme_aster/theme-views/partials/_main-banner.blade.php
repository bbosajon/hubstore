@php
    use App\Utils\Helpers;
@endphp
<section class="banner">
    <div class="container">
        <div class="card moble-border-0">
            <div class="p-0 p-sm-3 m-sm-1">
                <div class="row g-3">
                    <div class="col-xl-3 col-lg-4 d-none d-xl-block">
                        <div class="">
                            <ul class="dropdown-menu dropdown-menu--static bs-dropdown-min-width--auto">
                                @foreach($categories as $key=>$category)
                                    <li class="{{ $category->childes->count() > 0 ? 'menu-item-has-children' : '' }}">
                                        <a href="{{ route('category-products', ['slug' => $category['slug']]) }}">
                                            {{$category['name']}}
                                        </a>
                                        @if ($category->childes->count() > 0)
                                            <ul class="sub-menu">
                                                @foreach($category['childes'] as $subCategory)
                                                    <li class="{{ $subCategory->childes->count()>0 ? 'menu-item-has-children' : '' }}">
                                                        <a href="{{ route('category-products', ['slug' => $subCategory['slug']]) }}">
                                                            {{ $subCategory['name'] }}
                                                        </a>
                                                        @if($subCategory->childes->count()>0)
                                                            <ul class="sub-menu">
                                                                @foreach($subCategory['childes'] as $subSubCategory)
                                                                    <li>
                                                                        <a href="{{ route('category-products', ['slug' => $subSubCategory['slug']]) }}">
                                                                            {{ $subSubCategory['name'] }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                                <li class="d-flex justify-content-center mt-3 py-0">
                                    <a href="{{ route('products') }}"
                                       class="btn-link text-primary">
                                        {{ translate('view_all') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-6">
                        <div class="row g-2 g-sm-3 mt-lg-0">
                            <div class="col-12">
                                <div class="swiper-container shadow-sm rounded">
                                    <div class="swiper" data-swiper-loop="{{count($bannerTypeMainBanner) >1 ? 'true' : ''}}"
                                         data-swiper-navigation-next="null" data-swiper-navigation-prev="null">
                                        <div class="swiper-wrapper max-height-420px">
                                            @foreach($bannerTypeMainBanner as $key=>$banner)
                                                <div class="swiper-slide max-height-420px">
                                                    <a href="{{ $banner['url'] }}" class="h-100 rounded max-height-420px">
                                                        <img loading="lazy" alt="{{ translate('Banner') }}" class="dark-support rounded max-height-420px"
                                                            src="{{ getStorageImages(path:$banner['photo_full_url'], type:'banner') }}">
                                                    </a>
                                                </div>
                                            @endforeach
                                            @if(count($bannerTypeMainBanner)==0)
                                                <img src="{{ theme_asset('assets/img/placeholder/placeholder-2-1.png') }}"
                                                     loading="lazy" alt="{{ translate('Banner') }}" class="dark-support rounded">
                                            @endif
                                        </div>
                                        <div class="swiper-pagination"></div>
                                    </div>
                                </div>
                            </div>
                            @foreach($bannerTypeFooterBanner as $key=>$banner)
                                <div class="col-6 d-none d-sm-block aspect-2">
                                    <a href="{{ $banner['url'] }}" class="ad-hover h-100">
                                        <img src="{{  getStorageImages(path:$banner['photo_full_url'], type:'banner') }}"
                                             loading="lazy" alt="{{ translate('Banner') }}" class="dark-support rounded w-100 img-fit">
                                    </a>
                                </div>
                            @endforeach
                            @if(count($bannerTypeFooterBanner)==0)
                                <div class="col-6 d-none d-sm-block">
                                    <span class="ad-hover h-100 aspect-2 overflow-hidden rounded">
                                        <img src="{{ getStorageImages(path: null, type:'banner') }}"
                                             loading="lazy" alt="{{ translate('Banner') }}"
                                             class="dark-support rounded w-100 img-fit">
                                    </span>
                                </div>
                                <div class="col-6 d-none d-sm-block">
                                    <span class="ad-hover h-100 aspect-2 overflow-hidden rounded">
                                        <img src="{{ getStorageImages(path: null, type:'banner') }}"
                                             loading="lazy" alt="{{ translate('Banner') }}"
                                             class="dark-support rounded w-100 img-fit">
                                    </span>
                                </div>
                            @endif
                            @if(count($bannerTypeFooterBanner)==1)
                                <div class="col-6 d-none d-sm-block">
                                    <span class="ad-hover h-100 aspect-2 overflow-hidden rounded">
                                        <img src="{{ getStorageImages(path: null, type:'banner') }}"
                                             loading="lazy" alt="{{ translate('Banner') }}"
                                             class="dark-support rounded w-100">
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($random_coupon && count($random_coupon) > 0)
                        <div class="col-xl-3 d-none d-sm-block">
                            <div class="bg-primary-light happy-club-rightside-bar rounded p-3 mt-lg-3">
                                <h3 class="text-primary my-3">{{ translate('Happy_Club') }}</h3>
                                <p>{{ translate('collect_coupons_from_stores_and_apply_to_get_special_discount_from_stores') }}</p>
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($random_coupon as $coupon)
                                        <div
                                            class="club-card card custom-border-color hover-shadow flex-grow-1 click-to-copy-code"
                                            data-copy-code="{{ $coupon->code }}">
                                            <div class="d-flex flex-column gap-2 p-3">
                                                <h5 class="d-flex gap-2 align-items-center">
                                                    @if($coupon->coupon_type == 'free_delivery')
                                                        {{translate($coupon->coupon_type)}}
                                                        <img src="{{ theme_asset('assets/img/svg/delivery-car.svg') }}"
                                                             alt="{{ translate('free_delivery') }}" class="svg">
                                                    @else
                                                        {{ $coupon->discount_type == 'amount' ? webCurrencyConverter($coupon->discount) : $coupon->discount.'%'}}
                                                        {{translate('off')}}
                                                        <img src="{{ theme_asset('assets/img/svg/dollar.svg') }}" alt="{{ translate('Dollar') }}"
                                                             class="svg">
                                                    @endif
                                                </h5>
                                                <h6 class="fs-12">
                                                    <span class="text-muted">{{ translate('for') }}</span>
                                                    <span class="text-uppercase ">
                                                    @if($coupon->seller_id == '0')
                                                            {{ translate('All_Shops') }}
                                                        @elseif($coupon->seller_id == NULL)
                                                            <a class="shop-name" href="{{route('vendor-shop',['slug'=>getInHouseShopConfig(key:'slug')])}}">
                                                            {{ getInHouseShopConfig(key:'name') }}
                                                        </a>
                                                        @else
                                                            <a class="shop-name"
                                                              href="{{isset($coupon->seller->shop) ? route('vendor-shop',['slug'=>$coupon->seller->shop['slug']]) : 'javascript:'}}">
                                                            {{ isset($coupon->seller->shop) ? $coupon->seller->shop->name : translate('shop_not_found') }}
                                                        </a>
                                                        @endif
                                                </span>
                                                </h6>
                                                <h6 class="text-primary fs-12">{{ translate('code').': ' }}{{ $coupon->code }}</h6>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-xl-3 d-none d-sm-block">
                            @if($bannerTypeTopSideBanner)
                                <a href="{{ $bannerTypeTopSideBanner['url'] }}">
                                    <img alt="{{ translate('Banner') }}" class="dark-support rounded w-100"
                                        src="{{ getStorageImages(path: $bannerTypeTopSideBanner['photo_full_url'], type:'banner', source: theme_asset('assets/img/top-side-banner-placeholder.png')) }}">
                                </a>
                            @else
                                <img src="{{ theme_asset('assets/img/top-side-banner-placeholder.png') }}"
                                     class="dark-support rounded w-100" alt="{{ translate('Banner') }}">
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
