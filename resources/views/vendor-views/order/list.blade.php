@extends('layouts.vendor.app')
@section('title', translate('order_List'))

@push('css_or_js')
    <link href="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.css')}}"
          rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
            <h2 class="h1 mb-0">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/all-orders.png') }}" class="mb-1 mr-1"
                     alt="">
                <span class="page-header-title">
                    @if($status =='processing')
                        {{translate('packaging')}}
                    @elseif($status =='failed')
                        {{translate('failed_to_Deliver')}}
                    @elseif($status == 'all')
                        {{translate('all')}}
                    @else
                        {{translate(str_replace('_',' ',$status))}}
                    @endif
                </span>
                {{translate('orders')}}
            </h2>
            <span class="badge badge-soft-dark radius-50 fz-14">{{$orders->total()}}</span>
        </div>

        @if($status == 'all')
            <div class="card card-body mb-20">
                <h3 class="mb-20">{{ translate('Current_Order_Summary') }}</h3>
                <div class="row g-2">
                    <div class="col-lg-6 col-xl-3">
                        <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded"
                           href="{{ route('vendor.orders.list',['pending']) }}">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/pending.png') }}"
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
                           href="{{ route('vendor.orders.list',['confirmed']) }}">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="20"
                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/confirmed.png') }}" alt="">
                                <h4 class="mb-0">{{ translate('Confirmed') }}</h4>
                            </div>
                            <span class="text-success h3 mb-0 overflow-wrap-anywhere">
                            {{ $allOrdersInfo['confirmed_order'] }}
                        </span>
                        </a>
                    </div>

                    <div class="col-lg-6 col-xl-3">
                        <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_packaging"
                           href="{{ route('vendor.orders.list',['processing']) }}">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="20"
                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/packaging.png') }}" alt="">
                                <h4 class="mb-0">{{ translate('Packaging') }}</h4>
                            </div>
                            <span class="text-danger h3 mb-0 overflow-wrap-anywhere">
                            {{ $allOrdersInfo['processing_order'] }}
                        </span>
                        </a>
                    </div>

                    <div class="col-lg-6 col-xl-3">
                        <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_out-for-delivery"
                           href="{{ route('vendor.orders.list',['out_for_delivery']) }}">
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
                           href="{{ route('vendor.orders.list',['delivered']) }}">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="20"
                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/delivered.png') }}" alt="">
                                <h4 class="mb-0">{{ translate('Delivered') }}</h4>
                            </div>
                            <span class="text-primary h3 mb-0 overflow-wrap-anywhere">
                            {{ $allOrdersInfo['delivered_order'] }}
                        </span>
                        </a>
                    </div>

                    <div class="col-lg-6 col-xl-3">
                        <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_canceled cursor-pointer"
                           href="{{ route('vendor.orders.list',['canceled']) }}">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="20"
                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/canceled.png') }}" alt="">
                                <h4 class="mb-0">{{ translate('Canceled') }}</h4>
                            </div>
                            <span class="text-danger h3 mb-0 overflow-wrap-anywhere">
                            {{ $allOrdersInfo['canceled_order'] }}
                        </span>
                        </a>
                    </div>

                    <div class="col-lg-6 col-xl-3">
                        <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_returned cursor-pointer"
                           href="{{ route('vendor.orders.list',['returned']) }}">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="20"
                                     src="{{ dynamicAsset(path: 'public/assets/back-end/img/returned.png') }}" alt="">
                                <h4 class="mb-0">{{ translate('Returned') }}</h4>
                            </div>
                            <span class="text-warning h3 mb-0 overflow-wrap-anywhere">
                            {{ $allOrdersInfo['returned_order'] }}
                        </span>
                        </a>
                    </div>

                    <div class="col-lg-6 col-xl-3">
                        <a class="d-flex gap-3 align-items-center justify-content-between p-20 bg-section rounded order-stats_failed cursor-pointer"
                           href="{{ route('vendor.orders.list',['failed']) }}">
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

        <div class="card">
            <div class="card-body">
                <div class="px-3 py-4 light-bg">
                    <div class="row g-2 align-items-center flex-grow-1">
                        <div class="col-md-4">
                            <h5 class="text-capitalize d-flex gap-1">
                                {{translate('order_list')}}
                                <span class="badge badge-soft-dark radius-50 fs-12">{{$orders->total()}}</span>
                            </h5>
                        </div>
                        <div class="col-md-8 d-flex gap-3 flex-wrap flex-sm-nowrap justify-content-md-end">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                           placeholder="{{translate('search_by_Order_ID')}}" aria-label="Search orders"
                                           value="{{ $searchValue }}" required>
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </div>
                            </form>
                            <div class="dropdown">
                                <a type="button" class="btn btn-outline--primary text-nowrap"
                                   href="{{ route('vendor.orders.export-excel', [
                                                'delivery_man_id' => request('delivery_man_id'),
                                                'status' => $status, 'from' => $from, 'to' => $to,
                                                'filter' => $filter, 'searchValue' => $searchValue,
                                                'seller_id' => $vendorId, 'customer_id' => $customerId,
                                                'date_type' => $dateType,
                                                'payment_status' => request('payment_status'),
                                                 'order_current_status' => request('order_current_status'),
                                                  'order_amount_settlement' => request('order_amount_settlement')
                                            ]) }}">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}"
                                         class="excel" alt="">
                                    <span class="ps-2">{{ translate('export') }}</span>
                                </a>
                            </div>
                            <div class="position-relative">
                                @if((request('delivery_man_id') || $from || $to || $filter || $searchValue || $vendorId || $customerId || $dateType))
                                    <div
                                        class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2 z-2"
                                        style="--size: 12px;"></div>
                                @endif
                                <button type="button" class="btn btn--primary px-4" data-toggle="offcanvas"
                                        data-target="#offcanvasOrderFilter">
                                    <i class="fi fi-sr-settings-sliders"></i>
                                    {{ translate('Filter') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable"
                           class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th class="text-capitalize">{{translate('SL')}}</th>
                            <th class="text-capitalize">{{translate('order_ID')}}</th>
                            <th class="text-capitalize">{{translate('order_Date')}}</th>
                            <th class="text-capitalize">{{translate('customer_info')}}</th>
                            <th class="text-capitalize text-end">{{translate('total_amount')}}</th>
                            @if($status == 'all')
                                <th class="text-capitalize text-center">{{translate('order_Status')}} </th>
                            @else
                                <th class="text-capitalize">{{translate('payment_method')}} </th>
                            @endif
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $key=>$order)
                            <tr>
                                <td>
                                    {{ $orders->firstItem() + $key}}
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-column">
                                        <div class="d-flex align-items-center gap-1">
                                            <a class="title-color hover-c1"
                                               href="{{route('vendor.orders.details',$order['id'])}}">{{$order['id']}} {!! $order->order_type == 'POS' ? '<span class="text--primary">(POS)</span>' : '' !!}</a>
                                            @if( $order->edited_status == 1 && $order?->latestEditHistory?->order_due_payment_status == 'unpaid' && $order?->latestEditHistory?->order_due_payment_method != "offline_payment" && $order?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $order?->latestEditHistory?->order_due_amount > 0)
                                                <span class="flex-shrink-0" data-toggle="tooltip"
                                                      title="{{ translate('customer_will_pay_due_the_amount') }}">
                                                    <img width="14"
                                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/due-amount-icon.png') }}"
                                                         alt="">
                                                </span>
                                                @elseif($order->edited_status == 1 && $order?->latestEditHistory?->order_return_payment_status == 'pending' && $order?->latestEditHistory?->order_return_amount > 0)
                                                <span class="flex-shrink-0" data-toggle="tooltip"
                                                      title="{{ translate('you_need_to_return_the_excess_amount_to_the_customer.') }}">
                                                    <img width="16"
                                                         src="{{ dynamicAsset(path: 'public/assets/back-end/img/return-amount-icon.png') }}"
                                                         alt="">
                                                </span>
                                            @endif
                                        </div>
                                        @if($order->edited_status == 1)
                                            <span class="badge badge-soft-info w-max-content">
                                            {{ translate('Edited') }}
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>{{date('d M Y',strtotime($order['created_at']))}}</div>
                                    <div>{{date('H:i A',strtotime($order['created_at']))}}</div>
                                </td>
                                <td>
                                    @if($order->is_guest)
                                        <strong class="title-name">{{translate('guest_customer')}}</strong>
                                    @elseif($order->customer_id == 0)
                                        <strong class="title-name">
                                            {{ translate('Walk-In-Customer') }}
                                        </strong>
                                    @else
                                        @if($order->customer)
                                            <span class="text-body text-capitalize">
                                                <strong class="title-name">
                                                    {{ $order->customer['f_name'].' '.$order->customer['l_name'] }}
                                                </strong>
                                            </span>
                                            @if($order->customer['phone'])
                                                <a class="d-block title-color"
                                                   href="tel:{{ $order->customer['phone'] }}">{{ $order->customer['phone'] }}</a>
                                            @else
                                                <a class="d-block title-color"
                                                   href="mailto:{{ $order->customer['email'] }}">{{ $order->customer['email'] }}</a>
                                            @endif
                                        @else
                                            <label
                                                class="badge badge-danger fs-12">{{translate('invalid_customer_data')}}</label>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div>
                                        @php($orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order))
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $orderTotalPriceSummary['totalAmount']), currencyCode: getCurrencyCode()) }}
                                    </div>

                                    @if($order->payment_status=='paid')
                                        <span class="badge badge-soft-success">{{translate('paid')}}</span>
                                    @else
                                        <span class="badge badge-soft-danger">{{translate('unpaid')}}</span>
                                    @endif
                                </td>
                                @if($status == 'all')
                                    <td class="text-capitalize d-flex justify-content-center align-items-center">
                                        @if($order->order_status=='pending')
                                            <label
                                                class="badge badge-soft-primary">{{$order['order_status']}}</label>
                                        @elseif($order->order_status=='processing' || $order->order_status=='out_for_delivery')
                                            <label
                                                class="badge badge-soft-warning">{{str_replace('_',' ',$order['order_status'] == 'processing' ? 'packaging' : $order['order_status'])}}</label>
                                        @elseif($order->order_status=='delivered' || $order->order_status=='confirmed')
                                            <label
                                                class="badge badge-soft-success">{{$order['order_status']}}</label>
                                        @elseif($order->order_status=='returned')
                                            <label
                                                class="badge badge-soft-danger">{{$order['order_status']}}</label>
                                        @elseif($order['order_status']=='failed')
                                            <span class="badge badge-danger fs-12">
                                                    {{translate('failed_to_deliver')}}
                                            </span>
                                        @else
                                            <label
                                                class="badge badge-soft-danger">{{$order['order_status']}}</label>
                                        @endif
                                    </td>
                                @else
                                    <td class="text-capitalize">
                                        {{str_replace('_',' ',$order['payment_method'])}}
                                    </td>
                                @endif
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a class="btn btn-outline--success icon-btn"
                                           title="{{translate('view')}}"
                                           href="{{route('vendor.orders.details',[$order['id']])}}">
                                            <i class="fi fi-sr-eye d-flex"></i>
                                        </a>
                                        <a class="btn btn-outline-info icon-btn" target="_blank"
                                           title="{{translate('invoice')}}"
                                           href="{{route('vendor.orders.generate-invoice',[$order['id']])}}">
                                            <i class="fi fi-sr-down-to-line d-flex"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="d-flex justify-content-lg-end">
                        {{$orders->links()}}
                    </div>
                </div>

                @if(count($orders)==0)
                    @include('layouts.vendor.partials._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>

    <span id="message-date-range-text" data-text="{{ translate("invalid_date_range") }}"></span>
    <span id="js-data-example-ajax-url" data-url="{{ route('vendor.orders.customers') }}"></span>

    @include('vendor-views.order.partials._filter-offcanvas')

    @if($orders->isNotEmpty())
        @foreach($orders as $order)
            @include('vendor-views.order.partials.modal.order-edit-return-amount-modal', ['order' => $order])
        @endforeach
    @endif
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/jquery.dataTables.min.js')}}"></script>
    <script
        src="{{dynamicAsset(path: 'public/assets/back-end/vendor/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/vendor/order.js')}}"></script>
@endpush
