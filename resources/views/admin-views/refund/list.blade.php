@php use Illuminate\Support\Str; @endphp

@extends('layouts.admin.app')

@section('title', translate('Refund_Requests'))

@section('content')
    <div class="content container-fluid">

        <h2 class="h1 text-capitalize d-flex align-items-center gap-2 mb-3">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/refund-request-list.png')}}" alt="">
            {{translate('refund_request_list')}}
        </h2>

        <div class="card">
            <div class="p-3">
                <div class="row g-3 justify-content-between align-items-center">
                    <div class="col-12 col-md-4">
                        <div class=" d-flex align-items-center gap-1">
                            <h3 class="mb-0 fs-16">{{ translate('Pending Refund Requests List') }}</h3>
                            <span class="badge badge-soft-dark radius-50">{{ $refundList->total() }}</span>
                        </div>
                    </div>
                     <div class="col-12 col-md-8">
                        <div class="d-flex gap-3 flex-sm-nowrap align-items-center flex-wrap justify-content-md-end">
                            <form action="{{ url()->current() }}" method="GET"
                              class="flex-grow-1 max-w-300 min-w-100-mobile">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                        placeholder="{{ translate('search_by_order_id_or_refund_id') }}"
                                        aria-label="Search orders" value="{{ request('searchValue') }}">
                                    <div class="input-group-append search-submit">
                                        <button type="submit">
                                            <i class="fi fi-rr-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="dropdown d-flex gap-2 align-items-center">
                                <div class="position-relative">
                                    @if(!empty(request('sort_by')) || !empty(request('from_date')) || !empty(request('to_date')) || !empty(request('type')))
                                        <div class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2" style="--size: 14px;"></div>
                                    @endif
                                    <button type="button"
                                            class="btn {{ (!empty(request('sort_by')) || !empty(request('from_date')) || !empty(request('to_date')) || !empty(request('type'))) ? 'btn-primary' : 'btn-outline-primary' }}"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#PendingRefundRequestFilter">
                                        <i class="fi fi-rr-bars-filter"></i>
                                        {{ translate('Filter') }}
                                    </button>
                                </div>
                                <a class="btn btn-outline-primary"
                                   href="{{ route('admin.refund-section.refund.export', [
                                        'status' => request('status'),
                                        'searchValue' => request('searchValue'),
                                        'from_date' => request('from_date'),
                                        'to_date' => request('to_date'),
                                   ]) }}">
                                    <i class="fi fi-sr-inbox-in"></i>
                                    <span class="fs-12">{{ translate('export') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless">
                    <thead class="text-capitalize">
                    <tr>
                        <th>{{ translate('SL') }}</th>
                        <th class="text-center">{{ translate('refund_ID') }}</th>
                        <th>{{ translate('order_id') }} </th>
                        <th>{{ translate('product_info') }}</th>
                        <th>{{ translate('customer_info') }}</th>
                        <th class="text-end">{{ translate('total_amount') }}</th>
                        <th class="text-center">{{ translate('action') }}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($refundList as $key => $refund)
                        @php
                            $isProductUnavailable = $refund->product === null;
                            $productDetails = $refund?->orderDetails?->product_details
                                ? json_decode($refund->orderDetails->product_details, true)
                                : null;
                        @endphp
                        <tr>
                            <td>{{ $refundList->firstItem()+$key}}</td>
                            <td class="text-center">
                                <a href="{{route('admin.refund-section.refund.details', ['id' => $refund['id']]) }}"
                                   class="text-dark hover-primary">
                                    {{ $refund->id}}
                                </a>
                            </td>
                            <td>
                                <a href="{{route('admin.orders.details',['id'=>$refund->order_id]) }}"
                                   class="text-dark hover-primary">
                                    {{ $refund->order_id }}
                                </a>
                            </td>
                            <td>
                                <div class="{{ $isProductUnavailable ? 'table-row-disabled' : '' }}"
                                     data-bs-toggle="tooltip"
                                     title="{{ $isProductUnavailable ? translate('Product_has_been_deleted') : '' }}">
                                    <div class="d-flex flex-nowrap gap-2">
                                        <div class="d-block w-max-content">
                                            @if(!$isProductUnavailable)
                                                <a href="{{route('admin.products.view',['addedBy' => ($refund?->product?->added_by =='seller' ? 'vendor' : 'in-house'),'id' => $refund?->product?->id])}}">
                                                    <img
                                                        src="{{ getStorageImages(path: $refund?->product?->thumbnail_full_url, type: 'backend-product') }}"
                                                        class="avatar border" alt="">
                                                </a>
                                            @else
                                                <img
                                                    src="{{ getStorageImages(path: '', type: 'backend-product') }}"
                                                    class="avatar border" alt="">
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column gap-1">
                                            @if(!$isProductUnavailable)
                                                <a href="{{ route('admin.products.view',['addedBy'=>($refund->product->added_by =='seller'?'vendor' : 'in-house'), 'id'=>$refund->product->id]) }}"
                                                   class="text-dark fw-bold hover-primary">
                                                    {{ Str::limit($refund->product->name, 35) }}
                                                </a>
                                            @else
                                                <span class="text-dark fw-bold">
                                                    {{ Str::limit($productDetails['name'] ?? translate('product_name_not_found'), 35) }}
                                                </span>
                                            @endif

                                            <span class="fs-12">
                                                {{ translate('QTY') }} : {{ $refund?->orderDetails?->qty ?? 1 }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($refund->customer !=null)
                                    <div class="d-flex flex-column gap-1">
                                        <a href="{{route('admin.customer.view', [$refund->customer->id]) }}"
                                           class="text-dark fw-bold hover-primary">
                                            {{ $refund->customer->f_name. ' '. $refund->customer->l_name}}
                                        </a>
                                        @if($refund?->customer?->phone)
                                            <a href="tel:{{ $refund->customer->phone}}"
                                               class="text-dark hover-primary fs-12">
                                                {{ $refund->customer->phone}}
                                            </a>
                                        @else
                                            <a href="mailto:{{ $refund->customer['email'] }}"
                                               class="text-dark hover-primary fs-12">
                                                {{ $refund->customer['email'] }}
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <a href="javascript:" class="text-dark hover-primary">
                                        {{ translate('customer_not_found') }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 text-end">
                                    <div>
                                        {{ setCurrencySymbol(
                                            amount: usdToDefaultCurrency(amount: $refund->amount),
                                            currencyCode: getCurrencyCode()
                                        ) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline-info icon-btn"
                                       href="{{route('admin.refund-section.refund.details',['id'=>$refund['id']]) }}">
                                       <span data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                           <i class="fi fi-sr-eye d-flex"></i>
                                       </span>
                                    </a>
                                    @if($refund['status'] != 'refunded')
                                        <button class="btn btn-outline-warning icon-btn"
                                                data-bs-toggle="modal"
                                                data-bs-target="#refundModal-{{$refund['id']}}">
                                            <span data-bs-toggle="tooltip" data-bs-placement="top" title="Refund">
                                                <i class="fi fi-rr-refresh d-flex"></i>
                                            </span>
                                        </button>
                                        @if($refund['status'] != 'rejected')
                                            <button class="btn btn-outline-danger icon-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal-{{$refund['id']}}">
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Reject">
                                                    <i class="fi fi-rr-cross fs-12 d-flex"></i>
                                                </span>
                                            </button>
                                        @endif
                                        @if($refund['status'] != 'approved')
                                            <button class="btn btn-outline-success icon-btn"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#approveModal-{{$refund['id']}}">
                                                <span data-bs-toggle="tooltip" data-bs-placement="top" title="Approve">
                                                    <i class="fi fi-sr-check d-flex"></i>
                                                </span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!! $refundList->links() !!}
                </div>
            </div>

            @if(count($refundList) == 0)
                @include('layouts.admin.partials._empty-state',['text'=>'no_refund_request_found'],['image'=>'default'])
            @endif
        </div>
    </div>

    @include('admin-views.refund.partials._filter-offcanvas')

    @if(count($refundList ?? []) > 0)
        @foreach($refundList as $refund)
            @include('admin-views.refund.partials._approval-modal', ['refund' => $refund, 'walletStatus' => $walletStatus, 'walletAddRefund' => $walletAddRefund])
            @include('admin-views.refund.partials._reject-modal', ['refund' => $refund, 'walletStatus' => $walletStatus, 'walletAddRefund' => $walletAddRefund])
            @include('admin-views.refund.partials._refund-modal', ['refund' => $refund, 'walletStatus' => $walletStatus, 'walletAddRefund' => $walletAddRefund])
        @endforeach
    @endif
@endsection

@push('script')
    <script>
        $(function () {
            let slider = $("#price_range_slider");
            let minThumb = $("#thumb_min");
            let maxThumb = $("#thumb_max");
            let range = $(".slider-range");
            let minInput = $("#min_price");
            let maxInput = $("#max_price");

            let sliderMin = slider?.data('min-value') ?? 0;
            let sliderMax = slider?.data('max-value') ?? 100000000;

            let minValue = sliderMin;
            let maxValue = sliderMax;

            let isRtl = $('html').attr('dir') === 'rtl';

            function updateSlider() {
                let sliderWidth = slider.width();

                let minLeft = (((minValue - sliderMin) / (sliderMax - sliderMin)) * sliderWidth);
                let maxLeft = ((maxValue - sliderMin) / (sliderMax - sliderMin)) * sliderWidth;

                if (isRtl) {
                    minLeft = sliderWidth - minLeft;
                    maxLeft = sliderWidth - maxLeft;
                }

                minThumb.css(isRtl ? "insetInlineEnd" : "insetInlineStart", minLeft + "px");
                maxThumb.css(isRtl ? "insetInlineEnd" : "insetInlineStart", maxLeft + "px");

                range.css({
                    [isRtl ? 'insetInlineEnd' : 'insetInlineStart']: Math.min(minLeft, maxLeft) + "px",
                    width: Math.abs(maxLeft - minLeft) + "px",
                });

                minInput.val(minValue !== null ? minValue : minInput.attr('placeholder'));
                maxInput.val(maxValue !== null ? maxValue : maxInput.attr('placeholder'));

                let distance = maxValue - minValue;
                $('#slider_distance').text("$" + distance.toLocaleString());
            }

            function clamp(value, min, max) {
                return Math.min(Math.max(value, min), max);
            }

            function handleDrag(thumb, isMinThumb) {
                function startDrag(startX, startValue) {
                    let sliderWidth = slider.width();

                    function moveHandler(e) {
                        let pageX = e.pageX || (e.originalEvent.touches && e.originalEvent.touches[0].pageX);
                        if (!pageX) return;

                        let deltaX = isRtl ? (startX - pageX) : (pageX - startX);
                        let valueChange = (deltaX / sliderWidth) * (sliderMax - sliderMin);
                        let newValue = clamp(startValue + valueChange, sliderMin, sliderMax);

                        newValue = Math.round(newValue);

                        if (isMinThumb) {
                            minValue = Math.min(newValue, maxValue || sliderMax);
                        } else {
                            maxValue = Math.max(newValue, minValue || sliderMin);
                        }

                        updateSlider();
                    }

                    function stopHandler() {
                        $(document).off(".slider");
                    }

                    $(document).on("mousemove.slider touchmove.slider", moveHandler);
                    $(document).on("mouseup.slider touchend.slider touchcancel.slider", stopHandler);
                }

                thumb.on("mousedown touchstart", function (e) {
                    e.preventDefault();
                    let pageX = e.pageX || (e.originalEvent.touches && e.originalEvent.touches[0].pageX);
                    if (!pageX) return;

                    console.log("drag start", this.id, pageX);

                    let startValue = isMinThumb ? minValue : maxValue;
                    startDrag(pageX, startValue);
                });
            }

            minInput.on("input", function () {
                let inputValue = parseInt($(this).val(), 10);
                if (!isNaN(inputValue)) {
                    minValue = clamp(inputValue, sliderMin, maxValue || sliderMax);
                } else {
                    minValue = null;
                }
                updateSlider();
            });

            maxInput.on("input", function () {
                let inputValue = parseInt($(this).val(), 10);
                if (!isNaN(inputValue)) {
                    maxValue = clamp(inputValue, minValue || sliderMin, sliderMax);
                } else {
                    maxValue = null;
                }
                updateSlider();
            });

            handleDrag(minThumb, true);
            handleDrag(maxThumb, false);

            updateSlider();

            $(window).on("resize", function () {
                updateSlider();
            });
            $("form").on("reset", function () {
                console.log('form resetting');

                // Reset values to default
                minValue = sliderMin;
                maxValue = sliderMax;

                // Update inputs and slider visuals
                setTimeout(() => updateSlider(), 10);
            });
        });

    </script>
@endpush

