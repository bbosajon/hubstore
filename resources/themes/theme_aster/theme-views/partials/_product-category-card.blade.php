@php
    use App\Utils\Helpers;
    use App\Utils\ProductManager;
@endphp
<div class="swiper-slide h-auto">
    <div class="bg-white rounded slide-shadow p-2 overflow-hidden position-relative h-100">
        @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
            <span class="discount-badge">
                <span>
                    -{{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
                </span>
            </span>
        @else
        @endif
        @if(isset($product->flash_deal_status) && $product->flash_deal_status == 1)
            <div class="product__power-badge">
                <img width="10" height="18" src="{{theme_asset('assets/img/svg/power.svg')}}" alt="" class="svg text-white">
            </div>
        @endif
        <a href="javascript:"
           class="store-product d-flex flex-column gap-2 align-items-center text-center ov-hidden">
           <div class="store-product__top border rounded-10 mb-2 aspect-1 overflow-hidden">
                <span class="store-product__action preventDefault get-quick-view"
                      data-action="{{route('quick-view')}}"
                      data-product-id="{{$product['id']}}">
                    <i class="bi bi-eye fs-12"></i>
                </span>
                <img alt="{{ Str::limit($product['name'], 50) }}" loading="lazy" class="dark-support rounded aspect-1 img-fit"
                     src="{{ getStorageImages(path: $product?->thumbnail_full_url, type: 'product') }}">
            </div>
            <a class="fs-16 fw-bold text-truncate text-capitalize w-100 d-block text-center"  href="{{route('product', $product->slug)}}">
                {{ Str::limit($product['name'], 18) }}
                <div class="product__price d-flex justify-content-center align-items-baseline flex-wrap column-gap-2 mt-1">
                    @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                        <del class="product__old-price fs-14 lh-1 m-0">
                            {{webCurrencyConverter($product->unit_price)}}
                        </del>
                    @endif
                    <ins class="product__new-price fs-16 fw-extra-bold lh-1 m-0">
                        {{ getProductPriceByType(product: $product, type: 'discounted_unit_price', result: 'string') }}
                    </ins>
                </div>
            </a>
        </a>
    </div>
</div>

