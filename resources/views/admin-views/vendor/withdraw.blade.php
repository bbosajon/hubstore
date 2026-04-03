@extends('layouts.admin.app')

@section('title', translate('Withdraw_Request'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                {{translate('withdraw')}}
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row gy-1 align-items-center justify-content-between mb-4">
                    <div class="col-auto">
                        <h3 class="text-capitalize">
                        {{ translate('withdraw_request_table')}}
                            <span class="badge badge-info text-bg-info">{{ $withdrawRequests->total() }}</span>
                        </h3>
                    </div>
                    <div class="col-auto">
                        <div class="d-flex gap-3">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="form-group mb-0">
                                    <div class="input-group">
                                        <input id="datatableSearch_"
                                               type="search"
                                               name="searchValue"
                                               class="form-control"
                                               placeholder="{{ translate('search_by_Name_or_Shop') }}"
                                               aria-label="Search"
                                               value="{{ request('searchValue') }}">

                                        <div class="input-group-append search-submit">
                                            <button type="submit" class="btn btn-outline-secondary">
                                                <i class="fi fi-rr-search"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </form>
                            <div class="select-wrapper">
                                <select name="withdraw_status_filter" data-action="{{url()->current()}}" class="form-select min-w-120 withdraw-status-filter">
                                    <option value="all" {{request('approved') == 'all' ? 'selected' : ''}}>{{translate('all')}}</option>
                                    <option value="approved" {{request('approved') == 'approved' ? 'selected' : ''}}>{{translate('approved')}}</option>
                                    <option value="denied" {{request('approved') == 'denied' ? 'selected' : ''}}>{{translate('denied')}}</option>
                                    <option value="pending" {{request('approved') == 'pending' ? 'selected' : ''}}>{{translate('pending')}}</option>
                                </select>
                            </div>

                            <a type="button" class="btn btn-outline-primary" href="{{ route('admin.vendors.withdraw-list-export-excel') }}?approved={{request('approved')}}">
                                <i class="fi fi-sr-inbox-in"></i>
                                <span class="fs-12">{{ translate('export') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable" class="table table-hover table-borderless table-nowrap align-middle text-dark">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('amount')}}</th>
                            <th>{{ translate('name') }}</th>
                            <th>{{ translate('Shop') }}</th>
                            <th>{{translate('request_time')}}</th>
                            <th class="text-center">{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($withdrawRequests as $key => $withdrawRequest)
                            <tr>
                                <td>{{$withdrawRequests->firstItem() + $key }}</td>
                                <td>
                                    {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $withdrawRequest['amount']), currencyCode: getCurrencyCode(type: 'default')) }}
                                </td>

                                <td>
                                    @if (isset($withdrawRequest->seller))
                                        <a href="{{route('admin.vendors.view', $withdrawRequest->seller_id)}}" class="text-dark text-hover-primary">{{ $withdrawRequest->seller->f_name . ' ' . $withdrawRequest->seller->l_name }}</a>
                                    @else
                                        <span class="text-muted">{{translate('not_found')}}</span>
                                    @endif
                                </td>
                                <td>{{$withdrawRequest?->seller?->shop?->name ?? '-'}}</td>
                                <td>{{$withdrawRequest->created_at}}</td>
                                <td class="text-center">
                                    @if($withdrawRequest->approved == 0)
                                        <label class="badge badge-info text-bg-info">{{translate('pending')}}</label>
                                    @elseif($withdrawRequest->approved == 1)
                                        <label class="badge badge-success text-bg-success">{{translate('approved')}}</label>
                                    @elseif($withdrawRequest->approved == 2)
                                        <label class="badge badge-danger text-bg-danger">{{translate('denied')}}</label>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        @if (isset($withdrawRequest->seller))
                                            <a href="{{route('admin.vendors.withdraw_view', ['withdrawId' => $withdrawRequest['id'], 'vendorId'=>$withdrawRequest->seller['id']])}}"
                                               class="btn btn-outline-info icon-btn" title="{{translate('Details')}}">
                                                <i class="fi fi-rr-eye"></i>
                                            </a>
                                        @else
                                            <a href="javascript:">
                                                {{translate('action_disabled')}}
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-4 d-flex justify-content-end">
                    {{ $withdrawRequests->links() }}
                </div>
            @if(count($withdrawRequests) == 0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_withdraw_request_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
@endsection
