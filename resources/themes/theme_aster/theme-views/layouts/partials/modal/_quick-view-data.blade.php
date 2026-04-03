@php use App\Utils\Helpers; @endphp
<div class="modal-body">
    <div class="product-quickview">
        <button type="button" class="btn-close outside opacity-100 shadow top-0-lg" data-bs-dismiss="modal" aria-label="Close"></button>
        <div class="quickview-content">
            <div class="row align-items-start gy-4">
                <div class="col-lg-5">
                    <div class="pd-img-wrap position-relative h-100">
                        <div class="w-100 d-flex justify-content-center">
                            <div class="swiper-container quickviewSlider2 active-border border rounded aspect-1 border--gray">
                                <div class="product__actions d-flex align-items-start gap-12">
                                    @if($product->product_type === 'physical')
                                        <a href="javascript:void(0)" style="--size: 35px;"
                                           class="border text-warning d-inline-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-title="{{ translate('Physical_Product') }}" data-bs-placement="left">
                                            <img width="20" height="20" src="{{ theme_asset('assets/img/icons/product-type.svg') }}" class="svg" alt="">
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" style="--size: 35px;"
                                           class="border text-warning d-inline-flex align-items-center justify-content-center" data-bs-toggle="tooltip" data-bs-title="{{ translate('Digital_Product') }}" data-bs-placement="left">
                                            <img width="20" height="20" src="{{ theme_asset('assets/img/icons/digital-product.svg') }}" class="svg" alt="">
                                        </a>
                                    @endif
                                    <div class="d-flex flex-column gap-12">
                                        <a class="btn-wishlist add-to-wishlist cursor-pointer wishlist-{{$product['id']}} {{($wishlist_status == 1?'wishlist_icon_active':'')}}"
                                            style="--size: 35px;"
                                            title="{{ translate('add_to_wishlist') }}"
                                           data-action="{{route('store-wishlist')}}"
                                           data-product-id = "{{$product['id']}}">
                                            <i class="bi bi-heart fs-14 mt-2px"></i>
                                        </a>

                                        <a id="compare_list-{{$product['id']}}"
                                           class="btn-compare stopPropagation add-to-compare compare_list-{{$product['id']}} {{($compareList == 1?'compare_list_icon_active':'')}}"
                                           style="--size: 35px;"
                                           data-action="{{route('product-compare.index')}}"
                                           data-product-id="{{$product['id']}}"
                                           title="{{translate('add_to_compare_list')}}">
                                            <i class="bi bi-repeat"></i>
                                        </a>

                                        <div class="product-share-icons">
                                            <a href="javascript:" style="--size: 35px;" title="{{translate('Share')}}">
                                                <i class="bi bi-share-fill"></i>
                                            </a>
                                            <ul>
                                                <li>
                                                    <a href="javascript:"
                                                       class="share-on-social-media share_btn facebook"
                                                       style="--size: 20px;"
                                                       data-action="{{route('product',$product->slug)}}"
                                                       data-social-media-name="facebook.com/sharer/sharer.php?u=">
                                                        <i class="bi bi-facebook"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:" class="share-on-social-media share_btn twitter"
                                                        style="--size: 20px;"
                                                        data-action="{{route('product',$product->slug)}}"
                                                       data-social-media-name="twitter.com/intent/tweet?text=">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                            height="12" fill="currentColor"
                                                            class="bi bi-twitter-x" viewBox="0 0 16 16">
                                                            <path d="M12.6.75h2.454l-5.36 6.142L16 15.25h-4.937l-3.867-5.07-4.425 5.07H.316l5.733-6.57L0 .75h5.063l3.495 4.633L12.601.75Zm-.86 13.028h1.36L4.323 2.145H2.865l8.875 11.633Z"/>
                                                        </svg>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:" class="share-on-social-media share_btn linkedin"
                                                        style="--size: 20px;"
                                                        data-action="{{route('product',$product->slug)}}"
                                                       data-social-media-name="linkedin.com/shareArticle?mini=true&url=">
                                                        <i class="bi bi-linkedin"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:" class="share-on-social-media share_btn whatsapp"
                                                        style="--size: 20px;"
                                                        data-action="{{route('product',$product->slug)}}"
                                                       data-social-media-name="api.whatsapp.com/send?text=">
                                                        <i class="bi bi-whatsapp"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @if($product->images!=null && count($product->images_full_url)>0)
                                    <div class="swiper-wrapper">
                                        @if(json_decode($product->colors) && $product->color_images_full_url)
                                            @foreach ($product->color_images_full_url as $key => $photo)
                                                @if($photo['color'] != null)
                                                    <div class="swiper-slide position-relative"
                                                         id="preview-box-{{ $photo['color'] }}">
                                                        <div class="easyzoom easyzoom--overlay">
                                                            @if (getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                                                                <span class="product__discount-badge">
                                                                    -{{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
                                                                </span>
                                                            @endif
                                                            <a href="{{$photo['image_name']['path']}}">
                                                                <img class="dark-support rounded" alt=""
                                                                    src="{{ getStorageImages(path: $photo['image_name'], type:'product') }}">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="swiper-slide position-relative thumb_{{$key}}"
                                                         id="preview-box-{{ $photo['color'] }}">
                                                        <div class="easyzoom easyzoom--overlay">
                                                            @if (getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                                                                <span class="product__discount-badge">
                                                                    -{{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
                                                                </span>
                                                            @endif
                                                            <a href="{{ getStorageImages(path:$photo['image_name'], type:'product') }}">
                                                                <img class="dark-support rounded" alt=""
                                                                    src="{{ getStorageImages(path:$photo['image_name'], type:'product') }}">
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($product->images_full_url as $key => $photo)
                                                <div class="swiper-slide position-relative">
                                                    <div class="easyzoom easyzoom--overlay">
                                                        @if (getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                                                            <span class="product__discount-badge">
                                                                -{{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
                                                            </span>
                                                        @endif
                                                        <a href="{{ getStorageImages(path: $photo, type: 'product') }}">
                                                            <img class="dark-support rounded" alt=""
                                                                src="{{ getStorageImages(path: $photo, type: 'product') }}">
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 d-flex align-items-center gap-3 justify-content-between">
                            <div class="quickviewSliderThumb2 swiper-container active-border position-relative">
                                @if($product->images!=null && count($product->images_full_url)>0)
                                    <div class="swiper-wrapper auto-item-width width--4rem border--gray">
                                        @if(json_decode($product->colors) && $product->color_images_full_url)
                                            @foreach ($product->color_images_full_url as $key => $photo)
                                                @if($photo['color'] != null)
                                                    <div class="swiper-slide position-relative rounded border focus-preview-image-by-color"
                                                        style="--size: 54px;"
                                                        data-slide-id="preview-box-{{ str_replace('#','',$photo['color']) }}">
                                                        <img class="dark-support rounded" alt=""
                                                            src="{{getStorageImages(path: $photo['image_name'],type:'product')}}">
                                                    </div>
                                                @endif
                                            @endforeach

                                            @foreach ($product->color_images_full_url as $key => $photo)
                                                @if($photo['color'] == null)
                                                    <div class="swiper-slide position-relative rounded border slider-thumb-img-preview"
                                                        style="--size: 54px;"
                                                        data-thumb-key="thumb_{{$key}}">
                                                        <img class="dark-support rounded" alt=""
                                                            src="{{ getStorageImages(path:$photo['image_name'], type: 'product') }}">
                                                    </div>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach ($product->images_full_url as $key => $photo)
                                                <div class="swiper-slide position-relative rounded border slider-thumb-img-preview"
                                                    style="--size: 54px;"
                                                    data-thumb-key="thumb_{{$key}}">
                                                    <img src="{{ getStorageImages(path: $photo, type:'product') }}"
                                                         class="dark-support rounded" alt="">
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="d-lg-flex d-none align-items-center gap-2">
                                <div class="swiper-button-prev swiper-quickview-button-prev size-1-5rem position-static"></div>
                                <div class="swiper-button-next swiper-quickview-button-next size-1-5rem position-static"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="product-details-content mobile__views position-relative product-cart-option-container">

                        <form class="cart add-to-cart-details-form addToCartDynamicForm" action="{{ route('cart.add') }}"
                              data-errormessage="{{translate('please_choose_all_the_options')}}"
                              data-outofstock="{{translate('sorry_out_of_stock').'.'}}">
                            @csrf
                            <?php
                            if ($product->added_by == "seller") {
                                $shopName = $product->seller?->shop?->name ?? '';
                                $shopSlug = $product->seller?->shop?->slug ?? '';
                            } else {
                                $shopName = getInHouseShopConfig('name');
                                $shopSlug = getInHouseShopConfig('slug');
                            }
                            ?>
                            <div class="mb-3">
                                <a href="{{ route('vendor-shop', ['slug' => $shopSlug]) }}" class="d-block pb-1">
                                    {{ ucfirst($shopName) }}
                                </a>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <a class="product_title h3" href="{{route('product', $product['slug']) }}">
                                        {{ $product['name'] }}
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex gap-2 gap-sm-3 align-items-center flex-wrap mb-3">
                                @if($overallRating[0] != 0)
                                    <div class="d-flex gap-2 align-items-center">
                                        <div class="star-rating text-gold fs-12">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= (int)$overallRating[0])
                                                    <i class="bi bi-star-fill"></i>
                                                @elseif ($overallRating[0] != 0 && $i <= (int)$overallRating[0] + 1.1 && $overallRating[0] == ((int)$overallRating[0] + .50))
                                                    <i class="bi bi-star-half"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <span class="fs-14 text-muted">({{ $overallRating[0] }})</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                    <span class="text-primary fw-semibold">
                                        {{ $overallRating[1] }} {{ translate('Reviews') }}
                                    </span>
                                    </div>
                                    <div class="opacity-50 d-none d-sm-block">|</div>
                                @endif

                                @if(($product['product_type'] == 'physical') && ($product['current_stock']<=0))
                                    <p class="fw-semibold text-muted mb-0">{{ translate('out_of_stock') }}</p>
                                @else
                                    @if($product['product_type'] === 'physical')
                                        <p class="fw-semibold text-muted mb-0">
                                            <span class="product-details-stock-qty text-primary fw-semibold">{{ $product->current_stock }}</span>
                                            {{ translate('in_Stock') }}
                                        </p>
                                    @endif
                                @endif

                            </div>


                            @if($product['product_type'] == 'digital')
                                <div class="digital-product-authors mb-2">
                                    @if(count($productPublishingHouseInfo['data']) > 0)
                                        <div class="d-flex align-items-center g-2 me-2">
                                            <span class="text-capitalize digital-product-author-title">{{ translate('Publishing_House') }} :</span>
                                            <div class="item-list">
                                                @foreach($productPublishingHouseInfo['data'] as $publishingHouseName)
                                                    <a href="{{ route('products', ['publishing_house_id' => $publishingHouseName['id'], 'product_type' => 'digital', 'page'=>1]) }}"
                                                       class="text-base">
                                                        {{ $publishingHouseName['name'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if(count($productAuthorsInfo['data']) > 0)
                                        <div class="d-flex align-items-center g-2 me-2">
                                            <span class="text-capitalize digital-product-author-title">{{ translate('Author') }} :</span>
                                            <div class="item-list">
                                                @foreach($productAuthorsInfo['data'] as $productAuthor)
                                                    <a href="{{ route('products',['author_id' => $productAuthor['id'], 'product_type' => 'digital', 'page' => 1]) }}"
                                                       class="text-base">
                                                        {{ $productAuthor['name'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                        <div class="product__price d-flex flex-wrap align-items-end gap-2 mb-4">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-0">
                                {!! getPriceRangeWithDiscount(product: $product) !!}
                            </div>
                        </div>

                            <div class="">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                @if (count(json_decode($product->colors)) > 0)
                                    <div class="d-flex gap-4 align-items-center mb-3">
                                        <span class="text-muted">{{translate('color')}}</span>
                                        <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-3 pt-2">
                                            @foreach (json_decode($product->colors) as $key => $color)
                                                <li>
                                                    <label>
                                                        <input type="radio" hidden=""
                                                               id="{{ $product->id }}-color-{{ str_replace('#','',$color) }}"
                                                               name="color" value="{{ $color }}"
                                                            {{ $key == 0 ? 'checked' : '' }}
                                                        >
                                                        <span
                                                            class="color_variants rounded focus-preview-image-by-color p-0 {{ $key == 0 ? 'color_variant_active':''}}"
                                                            style="background: {{ $color }}; --outline:2px;"
                                                            data-slide-id="preview-box-{{ str_replace('#','',$color) }}"
                                                            id="color_variants_preview-box-{{ str_replace('#','',$color) }}"
                                                        ></span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                @foreach (json_decode($product->choice_options) as $choice)
                                    <div class="d-flex gap-4 align-items-center mb-4">
                                        <span class="text-muted">{{translate($choice->title)}}</span>
                                        <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2">
                                            @foreach ($choice->options as $key=>$option)
                                                <li>
                                                    <label>
                                                        <input type="radio" hidden=""
                                                               id="{{$choice->name}}-{{$option}}"
                                                               name="{{$choice->name}}" value="{{$option}}"
                                                               @if($key == 0) checked @endif >
                                                        <span>{{$option}}</span>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach

                                @php($extensionIndex=0)
                                @if($product['product_type'] == 'digital' && $product['digital_product_file_types'] && count($product['digital_product_file_types']) > 0 && $product['digital_product_extensions'])
                                    @foreach($product['digital_product_extensions'] as $extensionKey => $extensionGroup)
                                        <div class="d-flex gap-4 align-items-center mb-4">
                                            <span class="text-muted">
                                                {{ translate($extensionKey) }}
                                            </span>

                                            @if(count($extensionGroup) > 0)
                                                <ul class="option-select-btn custom_01_option flex-wrap weight-style--two gap-2">
                                                    @foreach($extensionGroup as $index => $extension)
                                                        <li>
                                                            <label>
                                                                <input type="radio" hidden
                                                                       name="variant_key"
                                                                       value="{{ $extensionKey.'-'.preg_replace('/\s+/', '-', $extension) }}"
                                                                    {{ $extensionIndex == 0 ? 'checked' : ''}}>
                                                                <span class="text-transform-none">{{ $extension }}</span>
                                                            </label>
                                                        </li>
                                                        @php($extensionIndex++)
                                                    @endforeach
                                                </ul>
                                            @endif

                                        </div>
                                    @endforeach
                                @endif

                                <div class="d-flex gap-4 flex-wrap align-items-center mb-4">
                                    <span class="text-muted">{{translate('Qty')}}</span>

                                    <div class="quantity quantity--style-two d-flex align-items-center justify-content-between w-130px h-40px rounded overflow-hidden">
                                        <span class="quantity__minus single-quantity-minus form-control h-100 bg--light w-32px">
                                            <i class="bi bi-dash"></i>
                                        </span>
                                        <input type="text" class="quantity__qty product_quantity__qty w-100 fs-12" name="quantity"
                                               data-details-page="1"
                                               value="{{ $initialProductConfig['quantity'] ?? 1 }}"
                                               min="{{ $product->minimum_order_qty ?? 1 }}"
                                               max="{{$product['product_type'] == 'physical' ? $product->current_stock : 100}}">
                                        <span class="quantity__plus single-quantity-plus form-control h-100 bg--light w-32px" {{($product->current_stock == 1?'disabled':'')}}>
                                            <i class="bi bi-plus"></i>
                                        </span>
                                    </div>
                                </div>
                                <input type="hidden" class="product-generated-variation-code" name="product_variation_code" data-product-id="{{ $product['id'] }}">
                                <input type="hidden" value="" class="product-exist-in-cart-list form-control w-50" name="key">
                                <div class="mb-30">
                                    <div class="bg-light w-100 rounded px-3 py-2">
                                        <div class="d-flex gap-1 align-items-center">
                                            <h4 class="flex-middle-gap-2 mb-0 fs-12">
                                                <span>
                                                    {{translate('total_price').':'}}
                                                </span>
                                                <span class="product-details-chosen-price-amount fs-18 text-primary">
                                                   {{ webCurrencyConverter(amount: $initialProductConfig['total_quantity_price']) }}
                                                </span>
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-3">
                                    <div class="product-add-and-buy-section d--flex flex-wrap gap-3" {!! $firstVariationQuantity <= 0 ? 'style="display: none;"' : '' !!}>
                                        @if(($product->added_by == 'admin' && (checkVendorAbility(type: 'inhouse', status: 'temporary_close') || checkVendorAbility(type: 'inhouse', status: 'vacation_status'))) || ($product->added_by == 'seller' && (checkVendorAbility(type: 'vendor', status: 'temporary_close', vendor: $product->seller->shop) || checkVendorAbility(type: 'vendor', status: 'vacation_status', vendor: $product->seller->shop))))
                                            <button type="button" class=" btn btn-secondary fs-16" data-bs-toggle="modal" data-bs-target="#buyNowModal" data-bs-dismiss="#quickViewModal"
                                                    disabled>{{ translate('buy_now') }}
                                            </button>
                                            <button type="button" class=" btn btn-primary fs-16" disabled>
                                                {{ translate('add_to_Cart') }}
                                            </button>
                                        @else
                                            <button type="button"
                                                    class="btn btn-secondary fs-16 product-buy-now-button"
                                                    data-form=".add-to-cart-details-form"
                                                    data-auth="{{( getWebConfig(name: 'guest_checkout') == 1 || Auth::guard('customer')->check() ? 'true':'false')}}"
                                                    data-route="{{ route('shop-cart') }}"
                                            >
                                                {{ translate('buy_now') }}
                                            </button>
                                            <button class="btn btn-primary fs-16 text-capitalize product-add-to-cart-button"
                                                    type="button"
                                                    data-form=".add-to-cart-details-form"
                                                    data-update="{{ translate('update_cart') }}"
                                                    data-add="{{ translate('add_to_cart') }}"
                                            >
                                                {{ $initialProductConfig['first_variant_in_cart'] ? translate('update_cart') : translate('add_to_cart') }}
                                            </button>
                                        @endif
                                    </div>

                                    @if(($product['product_type'] == 'physical'))
                                        <div class="product-restock-request-section collapse" {!! $firstVariationQuantity <= 0 ? 'style="display: block;"' : '' !!}>
                                            <button type="button"
                                                    class="btn request-restock-btn btn-outline-primary fw-semibold product-restock-request-button"
                                                    data-auth="{{ auth('customer')->check() }}"
                                                    data-form=".addToCartDynamicForm"
                                                    data-default="{{ translate('Request_Restock') }}"
                                                    data-requested="{{ translate('Request_Sent') }}"
                                            >
                                                {{ translate('Request_Restock')}}
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @if($product->added_by == 'admin')
                                    @if(checkVendorAbility(type: 'inhouse', status: 'temporary_close') || checkVendorAbility(type: 'inhouse', status: 'vacation_status'))
                                        <div class="alert alert-danger mt-3" role="alert">
                                            {{ translate('this_shop_is_temporary_closed_or_on_vacation.') }}
                                            {{ translate('You_cannot_add_product_to_cart_from_this_shop_for_now') }}
                                        </div>
                                    @endif
                                @elseif($product->added_by == 'seller')
                                    @if(checkVendorAbility(type: 'vendor', status: 'temporary_close', vendor: $product->seller->shop) || checkVendorAbility(type: 'vendor', status: 'vacation_status', vendor: $product->seller->shop))
                                        <div class="alert alert-danger mt-3" role="alert">
                                            {{ translate('this_shop_is_temporary_closed_or_on_vacation.') }}
                                            {{ translate('You_cannot_add_product_to_cart_from_this_shop_for_now') }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ theme_asset('assets/js/quick-view.js') }}"></script>
<script>
    'use strict';

    $('.modal').on('hidden.bs.modal', function () {
        if($(".modal:visible").length > 0) {
            setTimeout(function() {
                $('body').addClass('modal-open');
            },200)
        }
    });
    var quickviewSliderThumb2 = new Swiper(".quickviewSliderThumb2", {
        spaceBetween: 10,
        slidesPerView: "auto",
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true,
        // centeredSlides: true,

        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: ".swiper-quickview-button-next",
            prevEl: ".swiper-quickview-button-prev",
        },
    });
    var quickviewSlider2 = new Swiper(".quickviewSlider2", {
        // spaceBetween: 10,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        thumbs: {
            swiper: quickviewSliderThumb2,
        },
    });

    buyNow();
    focusPreviewImageByColor();
    shareOnSocialMedia();
    actionRequestForProductRestockFunctionality();
    addToCompare();
</script>
