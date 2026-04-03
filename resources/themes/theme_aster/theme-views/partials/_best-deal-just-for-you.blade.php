@php use App\Utils\Helpers;use App\Utils\ProductManager;use Illuminate\Support\Str; @endphp
<section class="flash-deal-dark-support">
    <div class="container">
        <div class="row g-3">
            @if(isset($dealOfTheDay->product))
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="px-30 py-20 d-none d-sm-block">
                            @php($overall_rating = getOverallRating($dealOfTheDay?->product->reviews))
                            <div class="today-best-deal d-flex justify-content-between gap-2 gap-sm-3">
                                <div class="d-flex flex-column gap-1 max-w-280px">
                                    <div class="mb-3">
                                        <div
                                            class="sub-title text-muted text-capitalize">{{ translate('do_not_miss_the_chance').'!' }}
                                        </div>
                                        <h2 class="title fs-27 fs-16-mobile text-primary fw-extra-bold text-capitalize">{{ translate('todays_best_deal') }}</h2>
                                    </div>
                                    <div class="mb-30 d-flex flex-column gap-1">
                                        <h6 class="text-capitalize line-limit-2 fs-16 mb-3">{{ $dealOfTheDay->product->name }}</h6>
                                        @if($overall_rating[0] != 0)
                                        <div class="d-flex gap-2 align-items-center mb-3">
                                            <div class="star-rating text-gold fs-12">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $overall_rating[0])
                                                        <i class="bi bi-star-fill"></i>
                                                    @elseif ($overall_rating[0] != 0 && $i <= $overall_rating[0] + 1.1)
                                                        <i class="bi bi-star-half"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span>({{ $dealOfTheDay->product->reviews->count() }})</span>
                                        </div>
                                        @endif

                                        <div class="product__price d-flex flex-wrap align-items-baseline gap-2 mb-3">
                                            @if(getProductPriceByType(product: $dealOfTheDay->product, type: 'discount', result: 'value') > 0)
                                                <del
                                                    class="product__old-price fs-22 fs-16-mobile">{{webCurrencyConverter($dealOfTheDay->product->unit_price)}}</del>
                                            @endif
                                            <ins class="product__new-price fs-28 fw-extra-bold fs-18-mobile">
                                                {{ getProductPriceByType(product: $dealOfTheDay->product, type: 'discounted_unit_price', result: 'string') }}
                                            </ins>
                                        </div>
                                        @if(getProductPriceByType(product: $dealOfTheDay->product, type: 'discount', result: 'value') > 0)
                                            <div class="mt-xl-2">
                                                <span class="product__save-amount discount-badge position-static fs-16-mobile">{{ translate('save') }}
                                                    {{ getProductPriceByType(product: $dealOfTheDay->product, type: 'discount', result: 'string') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex">
                                        <a href="{{route('product',$dealOfTheDay->product->slug)}}"
                                           class="btn btn-primary text-capitalize">{{ translate('Buy now') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img width="309" alt="{{ $dealOfTheDay?->product?->name }}" class="dark-support rounded"
                                         src="{{ getStorageImages(path: $dealOfTheDay?->product?->thumbnail_full_url, type: 'product') }}">
                                </div>
                            </div>
                        </div>
                        <div class="p-3 d-sm-none">
                            @php($overall_rating = getOverallRating($dealOfTheDay?->product->reviews))
                            <div class="today-best-deal">
                                <div class="">
                                    <div class="mb-3">
                                        <div
                                            class="sub-title text-muted text-capitalize fs-14">{{ translate('do_not_miss_the_chance').'!' }}
                                        </div>
                                        <h2 class="title fs-18 text-primary fw-extra-bold text-capitalize">{{ translate('todays_best_deal') }}</h2>
                                    </div>
                                    <div class="mb-3">
                                        <img width="106" alt="{{ $dealOfTheDay?->product?->name }}" class="dark-support rounded"
                                            src="{{ getStorageImages(path: $dealOfTheDay?->product?->thumbnail_full_url, type: 'product') }}">
                                    </div>
                                    <div class="mb-20 d-flex flex-column gap-1">
                                        <h6 class="text-capitalize line-limit-2 fs-14 mb-2">{{ $dealOfTheDay->product->name }}</h6>
                                        <div class="d-flex gap-2 align-items-center mb-2">
                                            <div class="star-rating text-gold fs-12">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $overall_rating[0])
                                                        <i class="bi bi-star-fill"></i>
                                                    @elseif ($overall_rating[0] != 0 && $i <= $overall_rating[0] + 1.1)
                                                        <i class="bi bi-star-half"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span>({{ $dealOfTheDay->product->reviews->count() }})</span>
                                        </div>


                                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                                            <div class="product__price d-flex flex-wrap align-items-baseline gap-1">
                                                @if(getProductPriceByType(product: $dealOfTheDay->product, type: 'discount', result: 'value') > 0)
                                                    <del
                                                        class="product__old-price fs-12 m-0">{{webCurrencyConverter($dealOfTheDay->product->unit_price)}}</del>
                                                @endif
                                                <ins class="product__new-price fw-extra-bold fs-14 m-0">
                                                    {{ getProductPriceByType(product: $dealOfTheDay->product, type: 'discounted_unit_price', result: 'string') }}
                                                </ins>
                                            </div>
                                            @if(getProductPriceByType(product: $dealOfTheDay->product, type: 'discount', result: 'value') > 0)
                                                <span class="product__save-amount discount-badge position-static">{{ translate('save') }}
                                                    {{ getProductPriceByType(product: $dealOfTheDay->product, type: 'discount', result: 'string') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex">
                                        <a href="{{route('product',$dealOfTheDay->product->slug)}}"
                                           class="btn btn-primary text-capitalize px-3 py-2">{{ translate('Grab_this_Deal') }}
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @elseif (isset($recommendedProduct->discount_type))
                <div class="col-lg-6">
                    <div class="card h-100">
                        <div class="p-30">
                            @php($overall_rating = getOverallRating($recommendedProduct?->reviews))
                            <div class="today-best-deal d-flex justify-content-between gap-2 gap-sm-3">
                                <div class="d-flex flex-column gap-1 max-w-280px">
                                    <div class="mb-3 mb-sm-4">
                                        <div
                                            class="sub-title text-muted mb-1 text-capitalize">{{ translate('do_not_miss_the_chance').'!' }}
                                        </div>
                                        <h2 class="title text-primary fw-extra-bold text-capitalize">{{ translate('todays_best_deal') }}</h2>
                                    </div>
                                    <div class="mb-3 mb-sm-4 d-flex flex-column gap-1">
                                        <h6 class="text-capitalize line-limit-2">{{ $recommendedProduct->name }}</h6>
                                        <div class="d-flex gap-2 align-items-center">
                                            <div class="star-rating text-gold fs-12">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $overall_rating[0])
                                                        <i class="bi bi-star-fill"></i>
                                                    @elseif ($overall_rating[0] != 0 && $i <= $overall_rating[0] + 1.1)
                                                        <i class="bi bi-star-half"></i>
                                                    @else
                                                        <i class="bi bi-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span>({{ $recommendedProduct->reviews->count() }})</span>
                                        </div>

                                        <div class="product__price d-flex flex-wrap align-items-end gap-2 mt-2">
                                            @if(getProductPriceByType(product: $recommendedProduct, type: 'discount', result: 'value') > 0)
                                                <del class="product__old-price">{{webCurrencyConverter($recommendedProduct->unit_price)}}</del>
                                            @endif
                                            <ins class="product__new-price">
                                                {{ getProductPriceByType(product: $recommendedProduct, type: 'discounted_unit_price', result: 'string') }}
                                            </ins>
                                        </div>
                                        @if(getProductPriceByType(product: $recommendedProduct, type: 'discount', result: 'value') > 0)
                                            <div class="mt-xl-2">
                                                <span class="product__save-amount">{{ translate('save') }}
                                                    {{ getProductPriceByType(product: $recommendedProduct, type: 'discount', result: 'string') }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="d-flex">
                                        <a href="{{route('product', $recommendedProduct->slug)}}"
                                           class="btn btn-primary text-capitalize">{{ translate('Grab_This_Deal') }}
                                        </a>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img width="309" alt="{{ $recommendedProduct?->name }}" class="dark-support rounded"
                                         src="{{ getStorageImages(path: $recommendedProduct['thumbnail_full_url'], type: 'product') }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="{{ (isset($dealOfTheDay->product) || isset($recommendedProduct->discount_type)) ? 'col-lg-6' : 'col-lg-12' }}">
                <div class="card h-100">
                    <div class="p-20">
                        <div class="d-flex flex-wrap justify-content-between gap-3 mb-3 align-items-center">
                            <h3 class="fw-extra-bold text-capitalize mb-0">
                                <span class="text-primary">{{translate('just')}}</span>
                                {{translate('for_you')}}
                            </h3>
                            <a href="{{route('products')}}" class="btn-link">
                                {{ translate('View_All') }}
                                <i class="bi bi-chevron-right text-primary"></i>
                            </a>
                        </div>
                        <div class="row g-2">
                            @foreach($justForYouProducts as $key => $product)
                                <div class="col-sm-6">
                                    <div class="hover-zoom-in card card-body p-2 border-0 rounded-10">
                                        @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                                            <span class="discount-badge">
                                                -{{ getProductPriceByType(product: $product, type: 'discount', result: 'string') }}
                                            </span>
                                        @endif
                                        <a href="{{ route('product', $product->slug) }}" class="">
                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="position-relative">
                                                    <img width="64" alt="{{ $product?->name }}" loading="lazy" class="w-64px dark-support rounded-10 border aspect-1"
                                                         src="{{ getStorageImages(path:$product->thumbnail_full_url, type: 'product') }}">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="fs-16 fw-bold line-clamp-1">{{ $product->name }}</div>
                                                    <div class="product__price d-flex flex-wrap align-items-baseline column-gap-2">
                                                        @if(getProductPriceByType(product: $product, type: 'discount', result: 'value') > 0)
                                                            <del class="product__old-price fs-14">
                                                                {{ webCurrencyConverter($product->unit_price) }}
                                                            </del>
                                                        @endif
                                                        <ins class="product__new-price fs-16 fw-extra-bold">
                                                            {{ getProductPriceByType(product: $product, type: 'discounted_unit_price', result: 'string') }}
                                                        </ins>
                                                    </div>
                                                     @if(isset($product->reviews) && $product->average_rating != 0)
                                                         <a href="{{ route('product', $product->slug) }}?review">
                                                        <div class="d-flex gap-2 align-items-center mt-1">
                                                            <div class="star-rating text-gold fs-12">
                                                                @for($inc = 0; $inc < 5; $inc++)
                                                                    @if($inc < $product->average_rating)
                                                                        <i class="bi bi-star-fill"></i>
                                                                    @else
                                                                        <i class="bi bi-star"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                             <span>({{ $product->reviews_count }})</span>
                                                        </div>
                                                         </a>
                                                     @endif
                                                </div>
                                            </div>
                                        </a>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
