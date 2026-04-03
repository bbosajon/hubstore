<section class="bg-primary-light section-bg flash-deal-dark-support mb-xl-3 mb-2">
    <div class="container">
        <div class="pb-lg-1">
            <div class="py-4">
                <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                    <h2 class="text-capitalize">{{ translate('top_rated_products') }}</h2>
                    <div class="d-flex align-items-center gap-3">
                        <a href="{{route('top-rated-products') }}" class="btn-link">
                            {{ translate('View_All') }}
                            <i class="bi bi-chevron-right text-primary"></i></a>
                        <div class="d-flex align-items-center gap-2">
                            <div class="swiper-button-prev top-rated-nav-prev position-static rounded-10"></div>
                            <div class="swiper-button-next top-rated-nav-next position-static rounded-10"></div>
                        </div>
                    </div>
                </div>
                <div class="swiper-container">
                    <div class="position-relative">
                        <div class="swiper" data-swiper-margin="16"
                             data-swiper-pagination-el="null" data-swiper-navigation-next=".top-rated-nav-next"
                             data-swiper-navigation-prev=".top-rated-nav-prev"
                             data-swiper-breakpoints='{"0": {"slidesPerView": "1"}, "340": {"slidesPerView": "2"}, "992": {"slidesPerView": "3"}, "1200": {"slidesPerView": "4"}, "1400": {"slidesPerView": "5"}}'>
                            <div class="swiper-wrapper">
                                @foreach($topRatedProducts as $key=> $product)
                                    <div class="swiper-slide">
                                        @include('theme-views.partials._product-large-card', ['product'=> $product])
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
