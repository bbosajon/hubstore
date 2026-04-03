@php use App\Utils\Helpers;use App\Utils\ProductManager;use Illuminate\Support\Str; @endphp

@if(isset($product))
    @php($overallRating = getOverallRating($product?->reviews))
    <div class="product border rounded-10 text-center d-flex align-items-center flex-column gap-10 p-2 get-view-by-onclick"
         data-link="{{route('product', $product->slug)}}">
         @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
             <span class="discount-badge">
             <span>
                 -{{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
             </span>
         </span>
         @endif
        <div class="product__top width--100 height-12-5-rem aspect-1 border rounded-10">
            <div class="product__actions d-flex flex-column gap-2">
                <a class="btn-wishlist stopPropagation add-to-wishlist cursor-pointer wishlist-{{$product['id']}} {{ isProductInWishList($product->id) == 1?'wishlist_icon_active':'' }}"
                   data-action="{{route('store-wishlist')}}"
                   data-product-id="{{$product['id']}}"
                   title="{{translate('add_to_wishlist')}}">
                    <i class="bi bi-heart"></i>
                </a>
                <a href="javascript:"
                   class="btn-compare stopPropagation add-to-compare compare_list-{{$product['id']}} {{ isProductInCompareList($product->id) == 1?'compare_list_icon_active':'' }}"
                   data-action="{{route('product-compare.index')}}"
                   data-product-id="{{$product['id']}}"
                   id="compare_list-{{$product['id']}}" title="{{translate('add_to_compare')}}">
                    <i class="bi bi-repeat"></i>
                </a>
                <a href="javascript:" class="btn-quickview stopPropagation get-quick-view"
                   data-action="{{route('quick-view')}}"
                   data-product-id="{{$product['id']}}" title="{{translate('quick_view')}}"
                >
                    <i class="bi bi-eye"></i>
                </a>
            </div>

            <div class="product__thumbnail align-items-center d-flex h-100 justify-content-center">
                <img src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'product') }}"
                     loading="lazy" class="dark-support rounded-10 h-100 w-100 object-fit-cover"
                     alt="{{ $product['name'] }}">
            </div>
        </div>
        <div class="product__summary d-flex flex-column align-items-center text-center gap-1 w-100 max-w-100">
            <h6 class="product__title text-truncate line-clamp-1 max-w-100">
                <a href="{{route('product',$product->slug)}}"
                   class="text-capitalize fs-16 fw-bold">{{ $product['name'] }}</a>
            </h6>
            <a href="{{route('product',$product->slug)}}">
                <div class="product__price d-flex flex-wrap align-items-baseline column-gap-2">
                    @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                        <del class="product__old-price fs-14">{{webCurrencyConverter($product->unit_price)}}</del>
                    @endif
                    <ins class="product__new-price fs-16 fw-extra-bold">
                        {{ getProductPriceByType(product: $product, type: 'discounted_unit_price', result: 'string') }}
                    </ins>
                </div>
            </a>
            @if($overallRating[0] != 0)
            <div class="d-flex gap-2 align-items-center">
                <span class="star-rating text-gold fs-12">
                    @for ($index = 1; $index <= 5; $index++)
                        @if ($index <= (int)$overallRating[0])
                            <i class="bi bi-star-fill"></i>
                        @elseif ($overallRating[0] != 0 && $index <= (int)$overallRating[0] + 1.1 && $overallRating[0] == ((int)$overallRating[0]+.50))
                            <i class="bi bi-star-half"></i>
                        @else
                            <i class="bi bi-star"></i>
                        @endif
                    @endfor
                </span>
                <span>({{ count($product->reviews) }})</span>
            </div>
            @endif

            <div class="text-muted fs-14">
                @if($product->added_by=='seller')
                    {{ isset($product->seller->shop->name) ? Str::limit($product->seller->shop->name, 20) : '' }}
                @elseif($product->added_by=='admin')
                    {{ getInHouseShopConfig(key:'name') }}
                @endif
            </div>
        </div>
    </div>
@endif
