<section class="py-3 flash-deal-dark-support">
    <div class="container">
        <h2 class="text-center mb-4 text-capitalize">{{ translate('recommended_for_you') }}</h2>
            <nav class="d-flex justify-content-center">
                <div class="nav nav-nowrap gap-3 gap-xl-5 nav--tabs hide-scrollbar recommended-tab" id="nav-tab" role="tablist">

                    @if(isset($featuredProductsList) && count($featuredProductsList) > 0)
                        <button class="active text-capitalize" id="nav-home-tab" data-bs-toggle="tab"
                                data-bs-target="#featured_product" role="tab" aria-controls="featured_product">
                            {{ translate('featured_products') }}
                        </button>
                    @endif

                    @if(isset($bestSellProduct) && count($bestSellProduct) > 0)
                        <button class="text-capitalize" data-bs-toggle="tab" data-bs-target="#best_selling" role="tab"
                                aria-controls="best_selling">
                            {{ translate('best_selling') }}
                        </button>
                    @endif

                    @if(isset($latestProductsList) && count($latestProductsList) > 0)
                        <button class="text-capitalize" data-bs-toggle="tab" data-bs-target="#latest_product" role="tab"
                                aria-controls="latest_product">
                            {{ translate('latest_products') }}
                        </button>
                    @endif

                </div>
            </nav>

            <div class="card mt-2">
                <div class="p-2 p-sm-3">
                    <div class="tab-content" id="nav-tabContent">

                        @if(isset($featuredProductsList) && count($featuredProductsList) > 0)
                            <div class="tab-pane fade show active" id="featured_product" role="tabpanel" tabindex="0">
                                <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                                    <a href="{{ route('featured-products') }}" class="btn-link text-capitalize">
                                        {{ translate('view_all') }} <i class="bi bi-chevron-right text-primary"></i>
                                    </a>
                                </div>
                                <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid minWidth-12rem slide-shadow-wrapper middle-align-info">
                                    @foreach($featuredProductsList as $product)
                                        @if($product)
                                            @include('theme-views.partials._product-large-card',['product'=>$product])
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(isset($bestSellProduct) && count($bestSellProduct) > 0)
                            <div class="tab-pane fade {{ !isset($featuredProductsList) || count($featuredProductsList) == 0 ? 'show active' : '' }}"
                                 id="best_selling" role="tabpanel" tabindex="0">
                                <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                                    <a href="{{ route('best-selling-products') }}" class="btn-link text-capitalize">
                                        {{ translate('view_all') }} <i class="bi bi-chevron-right text-primary"></i>
                                    </a>
                                </div>
                                <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid minWidth-12rem slide-shadow-wrapper middle-align-info">
                                    @foreach($bestSellProduct as $singleProduct)
                                        @include('theme-views.partials._product-large-card',['product'=>$singleProduct])
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(isset($latestProductsList) && count($latestProductsList) > 0)
                            <div class="tab-pane fade {{ (!isset($featuredProductsList) || count($featuredProductsList) == 0) && (!isset($bestSellProduct) || count($bestSellProduct) == 0) ? 'show active' : '' }}"
                                 id="latest_product" role="tabpanel" tabindex="0">
                                <div class="d-flex flex-wrap justify-content-end gap-3 mb-3">
                                    <a href="{{ route('latest-products') }}" class="btn-link text-capitalize">
                                        {{ translate('view_all') }} <i class="bi bi-chevron-right text-primary"></i>
                                    </a>
                                </div>
                                <div class="auto-col mobile-items-2 gap-2 gap-sm-3 recommended-product-grid minWidth-12rem slide-shadow-wrapper middle-align-info">
                                    @foreach($latestProductsList as $product)
                                        @if($product)
                                            @include('theme-views.partials._product-large-card',['product'=>$product])
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
    </div>
</section>
