@extends('layouts.admin.app')
@section('title', translate('expense_transaction'))

@section('content')
    <div class="content container-fluid ">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/order_report.png') }}" alt="">
                {{ translate('transaction_report') }}
            </h2>
        </div>

        @include('admin-views.report.transaction-report-inline-menu')

        <div class="card mb-3">
            <div class="card-body">
                <form action="#" id="form-data" method="GET">
                    <h3 class="mb-3">{{ translate('filter_Data') }}</h3>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-6 col-md-3">
                            <label class="mb-2">{{ translate('select_Date') }}</label>
                            <div class="select-wrapper">
                                <select class="form-select" name="date_type" id="date_type">
                                    <option
                                        value="this_year" {{ $date_type == 'this_year'? 'selected' : '' }}>{{ translate('this_year') }}</option>
                                    <option
                                        value="this_month" {{ $date_type == 'this_month'? 'selected' : '' }}>{{ translate('this_month') }}</option>
                                    <option
                                        value="this_week" {{ $date_type == 'this_week'? 'selected' : '' }}>{{ translate('this_week') }}</option>
                                    <option
                                        value="today" {{ $date_type == 'today'? 'selected' : '' }}>{{ translate('today') }}</option>
                                    <option
                                        value="custom_date" {{ $date_type == 'custom_date'? 'selected' : '' }}>{{ translate('custom_date') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="from_div">
                            <div>
                                <label class="mb-2">{{ translate('start Date') }}</label>
                                <input type="date" name="from" value="{{ $from}}" id="from_date"
                                       class="form-control __form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3" id="to_div">
                            <div>
                                <label class="mb-2">{{ translate('end Date') }}</label>
                                <input type="date" value="{{ $to}}" name="to" id="to_date"
                                       class="form-control __form-control">
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <button type="submit" class="btn btn-primary w-100" id="formUrlChange"
                                    data-action="{{ url()->current() }}">
                                {{ translate('filter') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-3 mb-3">
            <div class="d-flex flex-column gap-3 flex-grow-1 expense--content">
                <div class="card card-body">
                    <div class="d-flex align-items-center gap-3">
                        <img width="35" src="{{dynamicAsset(path: 'public/assets/back-end/img/expense.svg') }}" alt="">
                        <div class="info">
                            <h4 class="subtitle h1">
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $expenseTransactionSummary['total_expense']), currencyCode: getCurrencyCode()) }}
                            </h4>
                            <h5 class="subtext">
                                <span>{{ translate('total_Expense') }}</span>
                                <span class="ml-2" data-bs-toggle="tooltip"
                                      data-bs-title="{{ translate('free_delivery') }}, {{ translate('Referral_Discount') }}, {{ translate('coupon_discount_will_be_shown_here') }}">
                                    <i class="fi fi-rr-info"></i>
                                </span>
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/free-delivery.svg') }}" alt="">
                        <div class="info">
                            <h4 class="subtitle h1">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $expenseTransactionSummary['total_free_delivery'] + $expenseTransactionSummary['total_free_delivery_over_amount']), currencyCode: getCurrencyCode()) }}</h4>
                            <h5 class="subtext">{{ translate('free_Delivery') }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{dynamicAsset(path: 'public/assets/back-end/img/coupon-discount.svg') }}" alt="">
                        <div class="info">
                            <h4 class="subtitle h1">{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $expenseTransactionSummary['total_coupon_discount']), currencyCode: getCurrencyCode()) }}</h4>
                            <h5 class="subtext">
                                <span>{{ translate('coupon_Discount') }}</span>
                                <span class="ml-2" data-bs-toggle="tooltip"
                                      data-bs-title="{{ translate('discount_on_purchase_and_first_delivery_coupon_amount_will_be_shown_here') }}">
                                    <i class="fi fi-rr-info"></i>
                                </span>
                            </h5>
                        </div>
                    </div>
                </div>

                <div class="card card-body">
                    <div class="d-flex align-items-center gap-3">
                        <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/coupon-discount.svg') }}" alt="">
                        <div class="info">
                            <h4 class="subtitle h1">
                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $expenseTransactionSummary['total_referral_discount']), currencyCode: getCurrencyCode()) }}
                            </h4>
                            <h5 class="subtext">
                                <span>{{ translate('referral_Discount') }}</span>
                                <span class="ml-2" data-bs-toggle="tooltip"
                                      data-bs-title="{{ translate('discount_on_Referral_reward_amount_will_be_shown_here') }}">
                                    <i class="fi fi-rr-info"></i>
                                </span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($expenseTransactionChart['discount_amount'] as $amount)
                @php($amountArray[] = usdToDefaultCurrency(amount: $amount))
            @endforeach

            <div class="center-chart-area flex-grow-1">
                @include('layouts.admin.partials._apexcharts',[
                    'title' => 'expense_Statistics',
                    'statisticsValue' => $amountArray,
                    'label' => array_keys($expenseTransactionChart['discount_amount']),
                    'statisticsTitle' => 'total_expense_amount'
                ])
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
                    <h4 class="mb-0">
                        {{ translate('total_Transactions') }}
                        <span class="badge badge-info text-bg-info">{{ $expenseTransactionsTable->total() }}</span>
                    </h4>

                    <div class="d-flex flex-wrap gap-3">
                        <form action="" method="GET" class="mb-0">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="hidden" name="date_type" value="{{ $date_type }}">
                                    <input type="hidden" name="from" value="{{ $from }}">
                                    <input type="hidden" name="to" value="{{ $to }}">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                           value="{{ $search }}"
                                           placeholder="{{ translate('search_by_Order_ID_or_Transaction_ID') }}">
                                    <div class="input-group-append search-submit">
                                        <button type="submit">
                                            <i class="fi fi-rr-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <a href="{{ route('admin.transaction.expense-transaction-summary-pdf', ['search'=>request('search'),'date_type'=>request('date_type'), 'from'=>request('from'), 'to'=>request('to')]) }}"
                           class="btn btn-outline-primary text-nowrap">
                            <i class="fi fi-rr-document"></i>
                            <span class="fs-12">{{ translate('download_PDF') }}</span>
                        </a>

                        <a type="button" class="btn btn-outline-primary"
                           href="{{ route('admin.transaction.expense-transaction-export-excel', ['search'=>request('search'), 'date_type'=>request('date_type'), 'from'=>request('from'), 'to'=>request('to')]) }}">
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
                            <th>{{ translate('SL') }}</th>
                            <th>{{ translate('XID') }}</th>
                            <th>{{ translate('transaction_Date') }}</th>
                            <th>{{ translate('order_ID') }}</th>
                            <th>{{ translate('expense_Amount') }}</th>
                            <th>{{ translate('expense_Type') }}</th>
                            <th class="text-center">{{ translate('action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($expenseTransactionsTable as $key => $transaction)
                            <tr>
                                <td>{{ $expenseTransactionsTable->firstItem()+$key }}</td>
                                <td>{{ $transaction->orderTransaction->transaction_id }}</td>
                                <td>{{ date_format($transaction->updated_at, 'd F Y h:i:s a') }}</td>
                                <td>
                                    <a class="title-color"
                                       href="{{ route('admin.orders.details', ['id' => $transaction->id]) }}">
                                        {{ $transaction->id }}
                                    </a>
                                </td>
                                <td>
                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: ($transaction?->refer_and_earn_discount ?? 0) + (($transaction['seller_is'] == 'admin' || $transaction->coupon_discount_bearer == 'inhouse') ? $transaction->discount_amount:0) + ($transaction->free_delivery_bearer == 'admin' ? $transaction->extra_discount : 0)), currencyCode: getCurrencyCode()) }}
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if ($transaction->coupon_discount_bearer == 'inhouse' || ($transaction->coupon_discount_bearer == 'seller' && $transaction->seller_is == 'admin'))
                                            @if (isset($transaction->coupon->coupon_type))
                                                @if ($transaction->coupon->coupon_type == 'free_delivery')
                                                    <div>{{ translate('Free_Delivery_Promotion') }}</div>
                                                @else
                                                    <div>{{ ucwords(str_replace('_', ' ', ($transaction->coupon->coupon_type))) }}</div>
                                                @endif
                                            @elseif(!is_null($transaction->coupon_code) && $transaction?->coupon_code != 0)
                                                <div>{{ translate('Coupon_Discount') }}</div>
                                            @endif
                                        @endif
                                        @if ($transaction->free_delivery_bearer == 'admin')
                                            <div>{{ ucwords(str_replace('_', ' ', $transaction->extra_discount_type)) }}</div>
                                        @endif
                                        @if($transaction?->refer_and_earn_discount > 0)
                                            <div>{{ translate('Referral_Discount') }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('admin.transaction.pdf-order-wise-expense-transaction', ['id'=>$transaction->id]) }}"
                                           class="btn btn-outline-success icon-btn">
                                            <i class="fi fi-rr-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-lg-end">
                        {{ $expenseTransactionsTable->links() }}
                    </div>
                </div>
                @if(count($expenseTransactionsTable) == 0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/new/back-end/js/apexcharts.js') }}"></script>
    <script src="{{dynamicAsset(path: 'public/assets/new/back-end/js/apexcharts-data-show.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/new/back-end/js/admin/expense-report.js') }}"></script>
@endpush
