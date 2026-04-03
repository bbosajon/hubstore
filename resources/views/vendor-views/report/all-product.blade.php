@extends('layouts.vendor.app')
@section('title', translate('product_Report'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{asset('public/assets/back-end/img/seller_sale.png')}}" alt="">
                {{translate('product_report')}}
            </h2>
        </div>

        @include('vendor-views.report.product-report-inline-menu')

        <div class="card mb-3">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h4 class="mb-3">{{translate('filter_Data')}}</h4>
                    <div class="bg-section rounded p-12 p-sm-20 mb-20">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <div>
                                    <label for="date_type" class="text-dark">{{ translate('Select_Social_Media') }}</label>
                                    <select class="form-control __form-control" name="date_type" id="date_type">
                                        <option value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{translate('this_Year')}}</option>
                                        <option value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{translate('this_Month')}}</option>
                                        <option value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{translate('this_Week')}}</option>
                                        <option value="today" {{ $date_type == 'today'? 'selected' : '' }}>{{translate('today')}}</option>
                                        <option value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{translate('custom_Date')}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 d--none" id="from_div">
                                <div>
                                    <label for="from_date" class="text-dark">{{translate('Start_Date')}}</label>
                                    <input type="date" name="from" value="{{$from}}" id="from_date" class="form-control">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 d--none" id="to_div">
                                <div>
                                    <label for="to_date" class="text-dark">{{translate('End_Date')}}</label>
                                    <input type="date" value="{{$to}}" name="to" id="to_date" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ url()->current() }}" class="btn btn-secondary flex-grow-1 max-w-120">
                            {{translate('reset')}}
                        </a>
                        <button type="submit" class="btn btn--primary flex-grow-1 max-w-120 filter-btn">
                            {{translate('filter')}}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-body mb-3">
            <div class="row g-2">
                <div class="col-xl-8">
                    <div class="bg-section rounded-10 p-12 p-sm-20 h-100">
                        <div class="row g-2">
                            <div class="col-12">
                                <div class="d-flex gap-3 align-items-center bg-white rounded-10 p-2 overflow-wrap-anywhere">
                                    <div class="flex-shrink-0 aspect-1 border rounded-circle w-60px d-grid place-items-center bg-white">
                                        <img width="30" src="{{dynamicAsset(path: 'public/assets/back-end/img/packaging-new.png')}}" alt="" class="aspect-1">
                                    </div>
                                    <div>
                                        <h2 class="fs-26 fw-bold mb-2 text--primary">{{ $product_count['reject_product_count']+$product_count['active_product_count']+$product_count['pending_product_count'] }}</h2>
                                        <div class="text-dark">{{translate('total_Product')}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="bg-white rounded-10 px-3 py-2 overflow-wrap-anywhere">
                                    <h3 class="fs-20">
                                        <strong class="text-danger">{{ $product_count['reject_product_count'] }}</strong>
                                    </h3>
                                    <div class="text-dark">{{translate('rejected')}}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="bg-white rounded-10 px-3 py-2 overflow-wrap-anywhere">
                                    <h3 class="fs-20">
                                        <strong class="text-primary">{{ $product_count['pending_product_count'] }}</strong>
                                    </h3>
                                     <div class="text-dark">{{translate('pending')}}</div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="bg-white rounded-10 px-3 py-2 overflow-wrap-anywhere">
                                    <h3 class="fs-20">
                                        <strong class="text-success">{{ $product_count['active_product_count'] }}</strong>
                                    </h3>
                                     <div class="text-dark">{{translate('active')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex flex-column gap-3 h-100">
                        <div class="d-flex gap-3 align-items-center bg-success bg-opacity-10 rounded-10 p-2 px-sm-5 overflow-wrap-anywhere h-100">
                            <div class="flex-shrink-0 aspect-1 border rounded-circle w-60px d-grid place-items-center bg-white">
                                <img width="30" src="{{dynamicAsset(path: 'public/assets/back-end/img/total-product-sale.png')}}" alt="" class="aspect-1">
                            </div>
                            <div>
                                <h2 class="fs-26 fw-bold mb-2 text-success">{{ $total_product_sale }}</h2>
                                <div class="text-dark">{{translate('Total_Product_Sale')}}</div>
                            </div>
                        </div>
                        <div class="d-flex gap-3 align-items-center bg-warning bg-opacity-10 rounded-10 p-2 px-sm-5 overflow-wrap-anywhere h-100">
                            <div class="flex-shrink-0 aspect-1 border rounded-circle w-60px d-grid place-items-center bg-white">
                                <img width="30" src="{{dynamicAsset(path: 'public/assets/back-end/img/discount.png')}}" alt="" class="aspect-1">
                            </div>
                            <div>
                                <h2 class="fs-26 fw-bold mb-2 text-warning">
                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $total_discount_given), currencyCode: getCurrencyCode()) }}
                                </h2>
                                <div class="text-dark d-flex gap-1 align-items-center">
                                    {{translate('total_Discount_Given')}}
                                    <span data-toggle="tooltip" data-placement="top"
                                        title="{{translate('product_wise_discounted_amount_will_be_shown_here')}}">
                                        <img class="info-img" src="{{dynamicAsset(path: 'public/assets/back-end/img/info-circle.svg')}}"
                                            alt="{{translate('image')}}">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.vendor.partials._apexcharts',['title'=>'product_Statistics','statisticsValue'=>$chart_data['total_product'],'label'=> $chartDataTotalProductLabel,'statisticsTitle'=>'total_product','getCurrency'=>false])

        <div class="card mt-3">
            <div class="card-header border-0">
                <div class="d-flex flex-wrap w-100 gap-3 align-items-center justify-content-between">
                    <form action="" method="GET">
                        <!-- Search -->
                        <div class="input-group input-group-merge input-group-custom">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="tio-search"></i>
                                </div>
                            </div>
                            <input type="hidden" name="date_type" value="{{ $date_type }}">
                            <input type="hidden" name="from" value="{{ $from }}">
                            <input type="hidden" name="to" value="{{ $to }}">
                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                   placeholder="{{translate('search_Product_Name')}}" aria-label="Search orders"
                                   value="{{ $search }}" required>
                            <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                        </div>
                    </form>
                    <div class="dropdown">
                        <a type="button" class="btn btn-outline--primary text-nowrap" href="{{ route('vendor.report.all-product-excel', ['search' => request('search'), 'date_type' => request('date_type'), 'from' => request('from'), 'to' => request('to')]) }}">
                            <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" class="excel" alt="">
                            <span class="ps-2">{{ translate('export') }}</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" id="products-table">
                    <table class="table table-hover __table table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>
                                {{translate('product_Name')}}
                            </th>
                            <th>
                                {{translate('product_Unit_Price')}}
                            </th>
                            <th>
                                {{translate('total_Amount_Sold')}}
                            </th>
                            <th>
                                {{translate('total_Quantity_Sold')}}
                            </th>
                            <th>
                                {{translate('average_Product_Value')}}
                            </th>
                            <th>
                                {{translate('current_Stock_Amount')}}
                            </th>
                            <th>
                                {{translate('average_Ratings')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $key=>$product)
                            <tr>
                                <td>{{ $products->firstItem()+$key }}</td>
                                <td>
                                    <a href="{{route('vendor.products.view',[$product['id']])}}"
                                       class="media align-items-center gap-2 w-max-content text-dark">
                                        {{ \Illuminate\Support\Str::limit($product->name, 20) }}
                                    </a>
                                </td>
                                <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product->unit_price), currencyCode: getCurrencyCode()) }}</td>
                                <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: isset($product->orderDetails[0]->total_sold_amount) ? $product->orderDetails[0]->total_sold_amount : 0), currencyCode: getCurrencyCode()) }}</td>
                                <td>
                                    {{ isset($product->orderDetails[0]->product_quantity) ? $product->orderDetails[0]->product_quantity : 0 }}
                                </td>
                                <td>
                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: (
                                            isset($product->orderDetails[0]->total_sold_amount) ? $product->orderDetails[0]->total_sold_amount : 0) /
                                            (isset($product->orderDetails[0]->product_quantity) ? $product->orderDetails[0]->product_quantity : 1)
                                        ), currencyCode: getCurrencyCode()) }}
                                </td>
                                <td>
                                    {{ $product->product_type == 'digital' ? ($product->status==1 ? translate('available') : translate('not_available')) : $product->current_stock }}
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-1 lh-1">
                                        <div class="rating d-flex align-items-center gap-1">
                                            <i class="fi fi-sr-star text-warning-dark fs-12"></i>
                                            <span class="fw-semibold">
                                                {{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}
                                            </span>
                                        </div>
                                        <div>
                                            ({{$product->reviews->count()}})
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-center justify-content-md-end">
                    {!! $products->links() !!}
                </div>
            </div>
            @if(count($products)==0)
                @include('layouts.vendor.partials._empty-state',['text'=>'no_product_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts.js')}}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/apexcharts-data-show.js')}}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/vendor/product-report.js') }}"></script>
@endpush
