@extends('theme-views.layouts.app')

@section('title', $web_config['meta_title'])

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3">
        <?php
        $orderSuccessIds = session('order_success_ids');
        $isNewCustomerInSession = session('isNewCustomerInSession');
        session()->forget('order_success_ids');
        session()->forget('isNewCustomerInSession');
        ?>
        @include("theme-views.partials._order-success-modal",['orderSuccessIds' => $orderSuccessIds, 'isNewCustomerInSession' => $isNewCustomerInSession])

        @include('theme-views.partials._main-banner')

        @if ($flashDeal['flashDeal'] && $flashDeal['flashDealProducts']  && count($flashDeal['flashDealProducts']) > 0)
            @include('theme-views.partials._flash-deals')
        @endif

        @include('theme-views.partials._find-what-you-need')

        @include('theme-views.partials._clearance-sale', ['clearanceSaleProducts' => $clearanceSaleProducts])

        @if ($web_config['business_mode'] == 'multi' && count($topVendorsList) > 0 && $topVendorsListSectionShowingStatus)
            @include('theme-views.partials._top-stores')
        @endif

        @if (getFeaturedDealsProductList()->count() > 0)
            @include('theme-views.partials._featured-deals')
        @endif

        @include('theme-views.partials._recommended-product')
        @if($web_config['business_mode'] == 'multi')
            @include('theme-views.partials._more-stores')
        @endif

        @include('theme-views.partials._top-rated-products')

        @include('theme-views.partials._best-deal-just-for-you')

        @include('theme-views.partials._home-categories')
        @if (!empty($bannerTypeMainSectionBanner))
        <section class="">
            <div class="container">
                <div class="py-5 rounded position-relative">
                    <img src="{{ getStorageImages(path: $bannerTypeMainSectionBanner->photo_full_url??null, type:'banner') }}"
                         alt="{{ translate('Banner') }}" class="rounded position-absolute dark-support img-fit start-0 top-0 index-n1 flipX-in-rtl">
                    <div class="row justify-content-center">
                        <div class="col-10 py-4">
                            <h6 class="text-primary mb-2 text-capitalize">{{ translate('do_not_miss_today`s_deal') }}!</h6>
                            <h2 class="fs-2 mb-4 absolute-dark text-capitalize">{{ translate('let_us_shopping_today') }}</h2>
                            <div class="d-flex">
                                <a href="{{ $bannerTypeMainSectionBanner ? $bannerTypeMainSectionBanner->url : '' }}"
                                   class="btn btn-primary fs-16 text-capitalize">
                                    {{ translate('shop_now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif
    </main>
@endsection

@push('script')
    @if($orderSuccessIds)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('order_successfully');
                const orderModal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                orderModal.show();

                document.querySelectorAll('.copy-order-id').forEach(function(copyBtn) {
                    copyBtn.addEventListener('click', function() {
                        let orderTextEl = null;
                        orderTextEl = this.closest('tr')?.querySelector('.order-id-text');
                        if (!orderTextEl) {
                            orderTextEl = this.parentElement.querySelector('.order-id-text');
                        }
                        const orderText = orderTextEl?.textContent.trim();
                        if (orderText) {
                            navigator.clipboard.writeText(orderText).then(() => {
                                toastr.success('Order ID copied successfully!');
                            }).catch(err => {
                                console.warn('Clipboard error:', err);
                                toastr.warning('Unable to copy. Clipboard requires HTTPS or localhost.');
                            });
                        }
                    });
                });
                const closeBtn = document.getElementById('modal-close-btn');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        setTimeout(() => { orderModal.hide(); }, 600);
                    });
                }
            });
        </script>
    @endif

    @if(Request::is('/') && Cookie::has('popup_banner') === false && empty($orderSuccessIds))
        <script>
            $(document).ready(function () {
                $('#initialModal').modal('show');
            });
        </script>
        @php(Cookie::queue('popup_banner', 'off', 1))
    @endif
@endpush


