@extends('layouts.admin.app')

@section('title', translate('Customer_Loyalty_Point_Report'))

@section('content')
    <div class="content container-fluid">
        <h2 class="h1 mb-20 text-capitalize d-flex align-items-center gap-2">
            {{ translate('Loyalty_Point_Report') }}
        </h2>
        <div class="card card-body mb-3">
            <form action="{{route('admin.customer.loyalty.report')}}" method="get" id="filter-form">
                <div class="bg-section rounded-10 p-12 p-sm-20 mb-20">
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="form-group mb-0">
                                <label class="form-label" for="">
                                    {{ translate('Date_Range') }}
                                    <span class="text-danger">*</span>
                                    <span class="tooltip-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                        aria-label=" {{ translate('Select_a_date_range_to_view_loyalty_point_transactions') }}"
                                        data-bs-title=" {{ translate('Select_a_date_range_to_view_loyalty_point_transactions') }}">
                                        <i class="fi fi-sr-info"></i>
                                    </span>
                                </label>
                                <div class="position-relative">
                                    <span class="fi fi-sr-calendar icon-absolute-on-right"></span>
                                    <input
                                        type="text"
                                        class="js-daterangepicker form-control previous-date-true placeholder-mode-true"
                                        name="date"
                                        value="{{ request('date') }}"
                                        placeholder="{{ translate('Start_date') . ' - ' . translate('End_date') }}"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-0">
                                <label class="form-label" for="transaction_type">
                                    {{ translate('Transaction_type') }}
                                </label>
                                @php
                                $transaction_status=request()->get('transaction_type');
                                @endphp
                                <div class="select-wrapper">
                                    <select name="transaction_type" id="transaction_type" class="form-select" title="{{translate('select_transaction_type')}}">
                                        <option value="">{{ translate('all')}}</option>
                                        <option value="point_to_wallet" {{ isset($transaction_status) && $transaction_status=='point_to_wallet'?'selected':''}}>{{ translate('point_to_wallet')}}</option>
                                        <option value="order_place" {{ isset($transaction_status) && $transaction_status=='order_place'?'selected':''}}>{{ translate('order_place')}}</option>
                                        <option value="refund_order" {{ isset($transaction_status) && $transaction_status=='refund_order'?'selected':''}}>{{ translate('refund_order')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-0">
                                <label class="form-label" for="customer-id">
                                    {{ translate('Customer') }}
                                </label>
                                <select name="customer_id" class="custom-select">
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer['id'] }}"
                                            {{ (request('customer_id', 'all') === (string) $customer['id']) ? 'selected' : '' }}>
                                            {{ $customer['text'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end align-items-center gap-3">
                    <a href="{{ url()->current() }}" class="btn btn-secondary min-w-120">
                        {{ translate('reset') }}
                    </a>
                    <button type="submit" class="btn btn-primary min-w-120">{{ translate('filter') }}</button>
                </div>
            </form>
        </div>
        <div class="card card-body mb-3">
            <div class="d-flex flex-wrap gap-3">
                @php
                    $credit = $data[0]->total_credit??0;
                    $debit = $data[0]->total_debit??0;
                    $balance = $credit - $debit;
                @endphp
                <div class="flex-grow-1 d-flex gap-3 align-items-center bg-primary bg-opacity-10 rounded-10 p-3 px-sm-20 overflow-wrap-anywhere h-100">
                    <div class="flex-shrink-0 aspect-1 border rounded-circle w-60px d-grid place-items-center bg-white">
                        <img width="30" src="{{ dynamicAsset(path: 'public/assets/back-end/img/balance.png') }}" alt="" class="aspect-1">
                    </div>
                    <div>
                        <h2 class="fs-26 fw-bold mb-2">
                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $balance ?? 0)) }}
                        </h2>
                        <div class="text-dark">{{ translate('Balance') }}</div>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex gap-3 align-items-center bg-success bg-opacity-10 rounded-10 p-3 px-sm-20 overflow-wrap-anywhere h-100">
                    <div class="flex-shrink-0 aspect-1 border rounded-circle w-60px d-grid place-items-center bg-white">
                        <img width="30" src="{{ dynamicAsset(path: 'public/assets/back-end/img/credit.png') }}" alt="" class="aspect-1">
                    </div>
                    <div>
                        <h2 class="fs-26 fw-bold mb-2">
                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $credit ?? 0)) }}
                        </h2>
                        <div class="text-dark">{{ translate('Credit') }}</div>
                    </div>
                </div>
                <div class="flex-grow-1 d-flex gap-3 align-items-center bg-danger bg-opacity-10 rounded-10 p-3 px-sm-20 overflow-wrap-anywhere h-100">
                    <div class="flex-shrink-0 aspect-1 border rounded-circle w-60px d-grid place-items-center bg-white">
                        <img width="30" src="{{ dynamicAsset(path: 'public/assets/back-end/img/debit.png') }}" alt="" class="aspect-1">
                    </div>
                    <div>
                        <h2 class="fs-26 fw-bold mb-2">
                           {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $debit ?? 0)) }}
                        </h2>
                        <div class="text-dark">{{ translate('Debit ') }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-body">
            <div class="d-flex justify-content-end flex-wrap gap-3 align-items-center mb-4">
                <h3 class="mb-0 text-nowrap text-capitalize d-flex gap-1 align-items-center flex-grow-1">
                    {{ translate('transactions') }}
                    <span class="badge badge-info text-bg-info">{{ $transactions->total() }}</span>
                </h3>

                <form action="{{ url()->current() }}" method="GET" class="min-w-100-mobile min-w-280">
                    <div class="form-group">
                        <div class="input-group">
                            <input type="hidden" name="order_date" value="{{request('order_date')}}">
                            <input type="hidden" name="customer_joining_date" value="{{request('customer_joining_date')}}">
                            <input type="hidden" name="is_active" value="{{request('is_active')}}">
                            <input type="hidden" name="sort_by" value="{{request('sort_by')}}">
                            <input type="hidden" name="choose_first" value="{{request('choose_first')}}">
                            <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                    placeholder="{{ translate('search_By_Transaction_Id,Customer Name')}}"  aria-label="Search orders" value="{{ request('searchValue') }}">
                            <div class="input-group-append search-submit">
                                <button type="submit">
                                    <i class="fi fi-rr-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <a type="button" class="btn btn-outline-primary" href="{{route('admin.customer.loyalty.export',['transaction_type'=>$transaction_status,'customer_id'=>request('customer_id'),'to'=>request('to'),'from'=>request('from')])}}">
                    <i class="fi fi-sr-inbox-in"></i>
                    <span class="fs-12">{{ translate('export') }}</span>
                </a>
            </div>
            <div class="table-responsive">
                <table id="datatable"
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                    <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('transaction_ID') }}</th>
                            <th>{{ translate('created_at') }}</th>
                            <th>{{ translate('Customer_Name') }}</th>
                            <th>
                                {{ translate('credit') }}
                                ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})
                            </th>
                            <th>
                                {{ translate('debit') }}
                                ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})
                            </th>
                            <th>
                                {{ translate('balance') }}
                                ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})
                            </th>
                            <th>{{ translate('transaction_type') }}</th>
                            <th>{{ translate('reference') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $key => $transaction)
                        <tr scope="row">
                            <td >{{$key+$transactions->firstItem()}}</td>
                            <td>{{$transaction['transaction_id']}}</td>
                            <td class="text-center">{{date('Y/m/d '.config('timeformat'), strtotime($transaction['created_at']))}}</td>
                            <td><a href="{{route('admin.customer.view',['user_id'=>$transaction['user_id']])}}" class="text-dark text-hover-primary">{{Str::limit($transaction->user?$transaction->user->f_name.' '.$transaction->user->l_name:translate('not_found'),20)}}</a></td>
                            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['credit'])) }}</td>
                            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['debit'])) }}</td>
                            <td>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $transaction['balance'])) }}</td>
                            <td class="text-capitalize">
                                <span class="badge badge-{{$transaction['transaction_type']=='order_refund'
                                    ?'danger'
                                    :($transaction['transaction_type']=='loyalty_point'?'warning'
                                        :($transaction['transaction_type']=='order_place'
                                            ?'info'
                                            :'success'))
                                    }} text-bg-{{$transaction['transaction_type']=='order_refund'
                                    ?'danger'
                                    :($transaction['transaction_type']=='loyalty_point'?'warning'
                                        :($transaction['transaction_type']=='order_place'
                                            ?'info'
                                            :'success'))
                                    }}">
                                    {{translate($transaction['transaction_type'])}}
                                </span>
                            </td>
                            <td>{{$transaction['reference']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>


            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!!$transactions->appends(request()->query())->links()!!}
                </div>
            </div>
            @if(count($transactions)==0)
                @include('layouts.admin.partials._empty-state',['text'=>'no_data_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection
