@extends('layouts.admin.app')

@section('title', translate('order_List'))

@section('content')
    <div class="content container-fluid">
        <div>
            <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
                <h2 class="h1 mb-0">
                    <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/all-orders.png') }}" class="mb-1 mr-1"
                         alt="">
                    <span class="page-header-title">
                        @if ($status == 'processing')
                            {{ translate('packaging_Orders') }}
                        @elseif($status == 'failed')
                            {{ translate('failed_to_Deliver_Orders') }}
                        @elseif($status == 'all')
                            {{ translate('all_Orders') }}
                        @else
                            {{ translate(str_replace('_', ' ', $status)) }} {{ translate('Orders') }}
                        @endif
                    </span>
                </h2>
                <span class="badge text-dark bg-body-secondary fw-semibold rounded-45">{{ $orders->total() }}</span>
            </div>

            @if($status == 'all')
                <div class="card card-body mb-20">
                    <h3 class="mb-20">{{ translate('Current_Order_Summary') }}</h3>
                    <div class="row g-3">
                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded"
                               href="{{ route('admin.orders.list',['pending']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/pending.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Pending') }}</h4>
                                </div>
                                <span class="text-primary h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['pending_order'] }}
                            </span>
                            </a>
                        </div>
                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_confirmed"
                               href="{{ route('admin.orders.list',['confirmed']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/confirmed.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Confirmed') }}</h4>
                                </div>
                                <span class="text-success h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['confirmed_order'] }}
                            </span>
                            </a>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_packaging"
                               href="{{ route('admin.orders.list',['processing']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/packaging.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Packaging') }}</h4>
                                </div>
                                <span class="text-danger h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['processing_order'] }}
                            </span>
                            </a>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_out-for-delivery"
                               href="{{ route('admin.orders.list',['out_for_delivery']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/out-of-delivery.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Out_for_Delivery') }}</h4>
                                </div>
                                <span class="text-success h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['out_for_delivery_order'] }}
                            </span>
                            </a>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_delivered cursor-pointer"
                               href="{{ route('admin.orders.list',['delivered']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/delivered.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Delivered') }}</h4>
                                </div>
                                <span class="text-primary h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['delivered_order'] }}
                            </span>
                            </a>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_canceled cursor-pointer"
                               href="{{ route('admin.orders.list',['canceled']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/canceled.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Canceled') }}</h4>
                                </div>
                                <span class="text-danger h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['canceled_order'] }}
                            </span>
                            </a>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_returned cursor-pointer"
                               href="{{ route('admin.orders.list',['returned']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/returned.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Returned') }}</h4>
                                </div>
                                <span class="text-warning h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['returned_order'] }}
                            </span>
                            </a>
                        </div>

                        <div class="col-lg-6 col-xl-3">
                            <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_failed cursor-pointer"
                               href="{{ route('admin.orders.list',['failed']) }}">
                                <div class="d-flex gap-3 align-items-center">
                                    <img width="20"
                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/failed-to-deliver.png') }}"
                                         alt="">
                                    <h4 class="mb-0">{{ translate('Failed_to_Deliver') }}</h4>
                                </div>
                                <span class="text-danger h3 mb-0 overflow-wrap-anywhere">
                                {{ $allOrdersInfo['failed_order'] }}
                            </span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mt-3">
                <div class="card-body d-flex flex-column gap-20">

                    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center">
                        <h3 class="mb-0 d-flex gap-2 align-items-center">
                            {{ translate('order_List') }}
                            <span class="badge text-dark bg-body-secondary fw-semibold rounded-45">
                                {{ $orders->total() }}
                            </span>
                        </h3>

                        <div class="d-flex gap-3 align-items-center flex-wrap">
                            <form action="{{ url()->current() }}" method="GET" class="flex-grow-1">
                                <div class="form-group">
                                    <div class="input-group min-w-300">
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control" placeholder="{{ translate('search_by_Order_ID') }}"
                                               aria-label="Search by Order ID" value="{{ $searchValue }}">
                                        <div class="input-group-append search-submit">
                                            <button type="submit">
                                                <i class="fi fi-rr-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <a type="button" class="btn btn-outline-primary"
                               href="{{ route('admin.orders.export-excel', [
                                                    'delivery_man_id' => request('delivery_man_id'),
                                                    'status' => $status, 'from' => $from, 'to' => $to,
                                                    'filter' => $filter, 'searchValue' => $searchValue,
                                                    'seller_id' => $vendorId,
                                                    'customer_id' => $customerId,
                                                    'date_type' => $dateType,
                                                    'payment_status' => request('payment_status'),
                                                    'order_current_status' => request('order_current_status'),
                                                    'order_amount_settlement' => request('order_amount_settlement')
                                                    ]) }}">
                                <i class="fi fi-sr-inbox-in"></i>
                                <span class="fs-12">{{ translate('export') }}</span>
                            </a>
                            <div class="position-relative">
                                @if((request('delivery_man_id') || $from || $to || $filter || $searchValue || $vendorId || $customerId || $dateType))
                                    <div
                                        class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2"
                                        style="--size: 12px;"></div>
                                @endif
                                <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas"
                                        data-bs-target="#offcanvasOrderFilter">
                                    <i class="fi fi-sr-settings-sliders"></i>
                                    {{ translate('Filter') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless">
                            <thead class="text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('order_ID') }}</th>
                                <th class="text-capitalize">{{ translate('order_date') }}</th>
                                <th class="text-capitalize">{{ translate('customer_info') }}</th>
                                <th>{{ translate('Store') }}</th>
                                <th class="text-capitalize text-end">{{ translate('total_amount') }}</th>
                                @if ($status == 'all')
                                    <th class="text-center">{{ translate('order_status') }} </th>
                                @else
                                    <th class="text-capitalize">{{ translate('payment_method') }} </th>
                                @endif
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($orders as $key => $order)

                                <tr class="status-{{ $order['order_status'] }} class-all">
                                    <td class="">
                                        {{ $orders->firstItem() + $key }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-column">
                                            <div class="d-flex align-items-center gap-1">
                                                <a class="hover-primary text-dark"
                                                   href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">
                                                    {{ $order['id'] }}
                                                    {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}
                                                </a>
                                                @if($order->edited_status == 1 && $order?->latestEditHistory?->order_due_payment_status == 'unpaid' && $order?->latestEditHistory?->order_due_payment_method != "offline_payment" && $order?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $order?->latestEditHistory?->order_due_amount > 0)
                                                    <span class="flex-shrink-0" data-bs-toggle="tooltip"
                                                          title="{{ translate('customer_will_pay_due_the_amount') }}">
                                                        <img width="14"
                                                             src="{{ dynamicAsset(path: 'public/assets/back-end/img/due-amount-icon.png') }}"
                                                             alt="">
                                                    </span>
                                                @elseif($order->edited_status == 1 && $order?->latestEditHistory?->order_return_payment_status == 'pending' && $order?->latestEditHistory?->order_return_amount > 0)
                                                    <span class="flex-shrink-0" data-bs-toggle="tooltip"
                                                          title="{{ translate('you_need_to_return_the_excess_amount_to_the_customer.') }}">
                                                        <img width="16"
                                                             src="{{ dynamicAsset(path: 'public/assets/back-end/img/return-amount-icon.png') }}"
                                                             alt="">
                                                    </span>
                                                @endif
                                            </div>
                                            @if($order->edited_status == 1)
                                                <span class="badge badge-info text-bg-info w-max-content">
                                                        {{ translate('Edited') }}
                                                    </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ date('d M Y', strtotime($order['created_at'])) }},</div>
                                        <div>{{ date('h:i A', strtotime($order['created_at'])) }}</div>
                                    </td>
                                    <td>
                                        @if ($order->is_guest)
                                            <strong class="text-dark">{{ translate('guest_customer') }}</strong>
                                        @elseif($order->customer_id == 0)
                                            <strong class="text-dark">{{ translate('Walk-In-Customer') }}</strong>
                                        @else
                                            @if ($order->customer)
                                                <a class="text-capitalize hover-primary text-dark"
                                                   href="{{ route('admin.customer.view', ['user_id' => $order->customer['id']]) }}">
                                                    <strong
                                                        class="title-name fw-semibold">{{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</strong>
                                                </a>
                                                @if ($order->customer['phone'])
                                                    <a class="d-block text-dark"
                                                       href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                                @else
                                                    <a class="d-block text-dark"
                                                       href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                                @endif
                                            @else
                                                <label class="badge badge-danger text-bg-danger">
                                                    {{ translate('customer_not_found') }}
                                                </label>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($order->seller_id) && isset($order->seller_is))
                                            <a href="{{ $order->seller_is == 'seller' && $order->seller?->shop ? route('admin.vendors.view', ['id' => $order->seller->shop->id]) : route('admin.business-settings.inhouse-shop') }}"
                                               class="store-name fw-medium hover-primary text-dark text-wrap d-block max-w-360">
                                                @if ($order->seller_is == 'seller')
                                                    {{ isset($order->seller?->shop) ? $order->seller?->shop?->name : translate('Store_not_found') }}
                                                @elseif($order->seller_is == 'admin')
                                                    {{ getInHouseShopConfig(key: 'name') }}
                                                @endif
                                            </a>
                                        @else
                                            {{ translate('Store_not_found') }}
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div>
                                            @php($orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order))
                                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['totalAmount']), currencyCode: getCurrencyCode()) }}
                                        </div>

                                        @if ($order->payment_status == 'paid')
                                            <span
                                                class="fs-12 fw-medium text-success">{{ translate('paid') }}</span>
                                        @else
                                            <span
                                                class="fs-12 fw-medium text-danger">{{ translate('unpaid') }}</span>
                                        @endif
                                    </td>
                                    @if ($status == 'all')
                                        <td class="text-center text-capitalize">
                                            @if ($order['order_status'] == 'pending')
                                                <span class="badge badge-info text-bg-info">
                                                        {{ translate($order['order_status']) }}
                                                    </span>
                                            @elseif($order['order_status'] == 'processing' || $order['order_status'] == 'out_for_delivery')
                                                <span class="badge badge-warning text-bg-warning">
                                                        {{ str_replace('_', ' ', $order['order_status'] == 'processing' ? translate('packaging') : translate($order['order_status'])) }}
                                                    </span>
                                            @elseif($order['order_status'] == 'confirmed')
                                                <span class="badge badge-success text-bg-success">
                                                        {{ translate($order['order_status']) }}
                                                    </span>
                                            @elseif($order['order_status'] == 'failed')
                                                <span class="badge badge-danger text-bg-danger">
                                                        {{ translate('failed_to_deliver') }}
                                                    </span>
                                            @elseif($order['order_status'] == 'delivered')
                                                <span class="badge badge-success text-bg-success">
                                                        {{ translate($order['order_status']) }}
                                                    </span>
                                            @else
                                                <span class="badge badge-danger text-bg-danger">
                                                        {{ translate($order['order_status']) }}
                                                    </span>
                                            @endif
                                        </td>
                                    @else
                                        <td class="text-capitalize">
                                            {{ str_replace('_', ' ', $order['payment_method']) }}
                                        </td>
                                    @endif
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            @if($order->edited_status == 1 && $order?->edit_return_amount > 0)
                                                <button type="button" class="btn btn-outline-warning icon-btn"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#returnDueAmountModal-{{$order['id']}}">
                                                    <i class="fi fi-sr-undo-alt d-flex"></i>
                                                </button>
                                            @endif
                                            <a class="btn btn-outline-success btn-outline-success-dark icon-btn"
                                               title="{{ translate('view') }}"
                                               href="{{ route('admin.orders.details', ['id' => $order['id']]) }}">
                                                <i class="fi fi-sr-eye d-flex"></i>
                                            </a>
                                            <a class="btn btn-outline-success btn-outline-success-dark icon-btn"
                                               target="_blank" title="{{ translate('invoice') }}"
                                               href="{{ route('admin.orders.generate-invoice', [$order['id']]) }}">
                                                <i class="fi fi-sr-down-to-line d-flex"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <div class="d-flex justify-content-lg-end">
                            {!! $orders->links() !!}
                        </div>
                    </div>
                    @if (count($orders) == 0)
                        @include(
                            'layouts.admin.partials._empty-state',
                            ['text' => 'no_order_found'],
                            ['image' => 'default']
                        )
                    @endif
                </div>
            </div>
            <div class="js-nav-scroller hs-nav-scroller-horizontal d-none">
                <span class="hs-nav-scroller-arrow-prev d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="fi fi-rr-angle-left"></i>
                    </a>
                </span>

                <span class="hs-nav-scroller-arrow-next d-none">
                    <a class="hs-nav-scroller-arrow-link" href="javascript:">
                        <i class="fi fi-rr-angle-right"></i>
                    </a>
                </span>
                <ul class="nav nav-tabs page-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">{{ translate('order_list') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <span id="message-date-range-text" data-text="{{ translate('invalid_date_range') }}"></span>
    <span id="js-data-example-ajax-url" data-url="{{ route('admin.orders.customers') }}"></span>

    @include('admin-views.order.partials._filter-offcanvas')

    @if($orders->isNotEmpty())
        @foreach($orders as $order)
            @include('admin-views.order.partials.modal.order-edit-return-amount-modal',['order'=>$order])
        @endforeach
    @endif
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/order.js') }}"></script>
@endpush
