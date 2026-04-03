@if(count($clearanceSaleProducts) > 0)
    <section class="flash-deal-dark-support">
        <div class="container px-0 px-sm-12">
            <div class="position-relative z-1">
                <img src="{{ theme_asset('assets/img/clearance-sale-background.svg') }}" class="clearance-sale-bg-svg svg position-absolute w-100 h-100 z-n1 start-0" alt="">
                <div class="px-2 px-sm-4 py-md-4 py-3">
                    <div class="align-items-center d-flex justify-content-between gap-3 mb-3 mb-md-4 text-capitalize">
                        <div class="flex-grow-1">
                            <h2 class="text-uppercase fs-28 fs-20-mobile fw-extra-bold mb-1">
                                <span class="text-primary">{{ translate('Clearance') }}</span> {{ translate('Sale') }}
                            </h2>
                            <h3 class="text-capitalize mb-0 fs-22 fs-16-mobile opacity-50 font-medium clearance-save-more">
                                {{ translate('Save_More') }}
                            </h3>
                        </div>
                        <a href="{{ route('clearance-sale-products') }}" class="btn-link">
                            {{ translate('view_all') }} <i class="bi bi-chevron-right text-primary"></i>
                        </a>
                    </div>
                    <div class="swiper-container">
                        <div class="position-relative left-align-info">
                            <div class="swiper" data-swiper-loop="true" data-swiper-margin="16"
                                 data-swiper-pagination-el="null" data-swiper-navigation-next=".top-rated-nav-next"
                                 data-swiper-navigation-prev=".top-rated-nav-prev"
                                 data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "340": {"slidesPerView": "2", "spaceBetween": "8"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                                <div class="swiper-wrapper swiper-wrapper-rtl align-items-stretch">
                                    @foreach($clearanceSaleProducts as $key => $product)
                                        <div class="swiper-slide mx-w300 h-auto">
                                            @include('theme-views.partials._product-large-card', ['product'=> $product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="swiper-button-prev top-rated-nav-prev"></div>
                            <div class="swiper-button-next top-rated-nav-next"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
