@extends('layouts.admin.app')

@section('title', translate('vendor_product_sale_Report'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/seller-reports.png')}}" alt="">
                {{translate('vendor_Reports')}}
            </h2>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h3 class="mb-3">{{translate('filter_Data')}}</h3>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-6 col-md-3">
                            <label class="mb-2">{{translate('select_Vendor')}}</label>
                            <select class="custom-select" name="seller_id">
                                <option value="all">{{ translate('all_vendors') }}</option>
                                @foreach($sellers as $seller)
                                    <option
                                        value="{{$seller['id'] }}" {{$seller_id==$seller['id']?'selected':''}}>
                                        {{ $seller?->shop?->name ?? translate('shop_not_found') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="mb-2">{{translate('select_Date')}}</label>
                            <div class="select-wrapper">
                                <select class="form-select" name="date_type" id="date_type">
                                    <option
                                        value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                    <option
                                        value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                    <option
                                        value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                    <option
                                        value="today" {{ $date_type == 'today'? 'selected' : '' }}>{{translate('today')}}</option>
                                    <option
                                        value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div>
                                <label class="mb-2">{{translate('Start_Date')}}</label>
                                <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div class="">
                                <label class="mb-2">{{translate('End_Date')}}</label>
                                <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 filter-btn">
                            <button type="submit" class="btn btn-primary">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 mb-3">
            <div class="d-flex flex-column gap-3 flex-grow-1">
                <div class="card card-body">
                    <div class="d-flex gap-3 align-items-center">
                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/products.svg')}}" alt="back-end/img">
                        <div class="info">
                            <h4 class="subtitle h1">{{ $total_product }}</h4>
                            <h5 class="subtext">{{translate('products')}}</h5>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex gap-3 align-items-center mb-4">
                        <img width="35" src="{{ dynamicAsset(path: 'public/assets/back-end/img/cart.svg')}}" alt="back-end/img">
                        <div class="info">
                            <h4 class="subtitle h1">{{ $canceled_order+$ongoing_order+$delivered_order }}</h4>
                            <h5 class="subtext">{{translate('total_Orders')}}</h5>
                        </div>
                    </div>

                    <div class="coupon__discount d-flex flex-wrap gap-2 justify-content-around">
                        <div class="text-center">
                            <strong class="text-danger">{{ $canceled_order }}</strong>
                            <div class="d-flex gap-2 align-items-center justify-content-center">
                                <span>{{translate('canceled')}}</span>
                                <span class="ml-2" data-bs-toggle="tooltip" data-bs-title="{{translate('this_count_is_the_summation_of')}} {{translate('failed_to_deliver')}}, {{translate('canceled')}}, {{translate('and')}} {{translate('returned_orders')}}">
                                      <i class="fi fi-rr-info"></i>
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-primary">{{ $ongoing_order }}</strong>
                            <div class="d-flex gap-2 align-items-center justify-content-center">
                                <span>{{translate('ongoing')}}</span>
                                <span class="ml-2" data-bs-toggle="tooltip" data-bs-title="{{translate('this_count_is_the_summation_of')}} {{translate('pending')}}, {{translate('confirmed')}}, {{translate('packaging')}}, {{translate('out_for_delivery_orders')}}">
                                      <i class="fi fi-rr-info"></i>
                                </span>
                            </div>
                        </div>
                        <div class="text-center">
                            <strong class="text-success">{{ $delivered_order }}</strong>
                            <div class="d-flex gap-2 align-items-center justify-content-center">
                                <span>{{translate('completed')}}</span>
                                <span class="ml-2" data-bs-toggle="tooltip" data-bs-title="{{translate('this_count_is_the_summation_of_delivered_orders')}}">
                                      <i class="fi fi-rr-info"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex gap-3 align-items-center">
                        <img width="35" src="{{ dynamicAsset(path: 'public/assets/back-end/img/deliveryman.svg')}}" alt="back-end/img">
                        <div class="info">
                            <h4 class="subtitle h1">
                                {{ $deliveryman }}
                            </h4>
                            <h5 class="subtext">{{translate('total_Deliveryman')}}</h5>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($chart_data['order_amount'] as $amount)
                @php($chartVal[] = usdToDefaultCurrency(amount: $amount))
            @endforeach
            <div class="center-chart-area flex-grow-1">
                @include('layouts.admin.partials._apexcharts',['title'=>'order_statistics','statisticsValue'=>$chartVal,'label'=> $chartDataOrderAmountLabel, 'statisticsTitle'=>'total_order_amount'])
            </div>
            <div class="flex-grow-1">
                <div class="card h-100 bg-white payment-statistics-shadow">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
                        <div class="earning-statistics-content">
                            <img class="mb-4" src="{{ dynamicAsset(path: 'public/assets/back-end/img/earnings.svg')}}"
                                 alt="back-end/img">
                            <h4 class="subtitle">{{translate('Total Shop Earnings')}}</h4>
                            <h3 class="title h2">
                                {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_store_earning), currencyCode: getCurrencyCode()) }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-4">
                    <h3 class="mb-0 mr-auto">
                        {{ translate('total_Vendor') }}
                        <span class="badge badge-info text-bg-info">{{ $orders->total() }}</span>
                    </h3>

                    <div class="d-flex flex-wrap gap-3">
                        <form action="{{ url()->full() }}" method="GET" class="mb-0">
                            <div class="input-group">
                                <input type="hidden" name="seller_id" value="{{ $seller_id }}">
                                <input type="hidden" name="date_type" value="{{ $date_type }}">
                                <input type="hidden" name="from" value="{{ $from }}">
                                <input type="hidden" name="to" value="{{ $to }}">
                                <input id="datatableSearch_" type="search" value="{{ $search }}" name="search"
                                       class="form-control" placeholder="{{translate('search_by_vendor_info')}}" aria-label="Search orders"
                                      >
                                <div class="input-group-append search-submit">
                                    <button type="submit">
                                        <i class="fi fi-rr-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <a type="button" class="btn btn-outline-primary" href="{{ route('admin.report.vendor-report-export', ['date_type'=>request('date_type'), 'seller_id'=>request('seller_id'),'from'=>request('from'), 'to'=>request('to'), 'search'=>request('search')]) }}">
                            <i class="fi fi-sr-inbox-in"></i>
                            <span class="fs-12">{{ translate('export') }}</span>
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable"
                           class="table __table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{ translate('vendor-Info') }}</th>
                            <th>{{translate('total_Order')}}</th>
                            <th>{{translate('commission')}}</th>
                            <th class="text-center">{{translate('refund_Rate')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $key=>$order)
                            <tr>
                                <td>{{ $orders->firstItem()+$key }}</td>
                                <td>
                                    <div>
                                        @if (isset($order->seller->shop))
                                            <a class="title-color"
                                               href="{{ route('admin.vendors.view', ['id' => $order->seller->id]) }}">
                                                <h6 class="mb-1">
                                                    {{ \Str::limit($order->seller->shop->name, 20)}}
                                                </h6>
                                                <span class="mb-1 text-capitalize">
                                                   {{$order->seller->f_name.' '.$order->seller->l_name}}
                                                </span>
                                            </a>
                                        @else
                                            {{translate('not_found')}}
                                        @endif

                                    </div>
                                </td>
                                <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->total_order_amount), currencyCode: getCurrencyCode()) }}</td>
                                <td>{{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $order->total_admin_commission), currencyCode: getCurrencyCode()) }}</td>
                                <td class="text-center">
                                        <?php
                                        $arr = array();
                                        if ($refunds) {
                                            foreach ($refunds as $refund) {
                                                $arr += array(
                                                    $refund['payer_id'] => $refund['total_refund_amount']
                                                );
                                            }
                                        }
                                        if (array_key_exists($order->seller_id, $arr)) {
                                            echo number_format(($arr[$order->seller_id] / $order->total_order_amount) * 100, 2) . '%';
                                        } else {
                                            echo '0%';
                                        }
                                        ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        @if($order->seller_id)
                                            <a href="{{ route('admin.vendors.view', ['id'=>$order->seller_id]) }}"
                                               class="btn btn-outline-primary square-btn btn-sm">
                                                <i class="fi fi-rr-eye"></i>
                                            </a>
                                        @else
                                            <span class="btn btn-outline-primary square-btn btn-sm disabled">
                                                <i class="fi fi-rr-eye"></i>
                                            </span>
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
                        {!! $orders->links() !!}
                    </div>
                </div>
                @if(count($orders) <= 0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_order_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/new/back-end/js/apexcharts.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/new/back-end/js/apexcharts-data-show.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/new/back-end/js/admin/seller-earning-report.js') }}"></script>
@endpush
