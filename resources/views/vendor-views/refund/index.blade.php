@php use Illuminate\Support\Str; @endphp
@extends('layouts.vendor.app')

@section('title', translate('refund_list'))

@section('content')
    <div class="content container-fluid">
        <h2 class="h1 text-capitalize d-flex align-items-center gap-2 mb-3">
            <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/refund-request-list.png')}}" alt="">
            {{translate('refund_request_list')}}
        </h2>
        <div class="card">
            <div class="p-3">
                <div class="row g-2 justify-content-between align-items-center">
                    <div class="col-12 col-md-4">
                        <div class=" d-flex align-items-center gap-1">
                            <h4 class="mb-0 fs-16">{{ translate('Pending Refund Requests List') }}</h4>
                            <span class="badge badge-soft-dark radius-50 px-2 py-1">{{ $refundList->total() }}</span>
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="d-flex gap-3 flex-sm-nowrap align-items-stretch flex-wrap justify-content-md-end">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group input-group-merge input-group-custom">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="tio-search"></i>
                                        </div>
                                    </div>
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="{{translate('search_by_order_id_or_refund_id')}}"
                                        aria-label="Search orders" value="{{ request('search') }}">
                                    <button type="submit" class="btn btn--primary">{{translate('search')}}</button>
                                </div>
                            </form>
                            <div class="dropdown">
                                <a type="button" class="btn h-45 px-4 btn-outline--primary text-nowrap h-100"
                                   href="{{route('vendor.refund.export',['status'=> request('status'),'search'=>request('search'), 'from_date' => request('from_date'), 'to_date' => request('to_date')])}}">
                                    <i class="fi fi-sr-inbox-in"></i>
                                    <span class="ps-1">{{ translate('export') }}</span>
                                </a>
                            </div>

                            <div class="position-relative">
                                @if(!empty(request('from_date')) || !empty(request('to_date')))
                                    <div class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2 z-2" style="--size: 12px;"></div>
                                @endif
                                    <button type="button"  @if(!empty(request('from_date')) || !empty(request('to_date'))) class="btn btn--primary px-4 h-100" @else class="btn btn-outline--primary px-4 h-100" @endif
                                            class="btn btn-outline--primary h-45 px-4 position-relative"
                                            data-toggle="offcanvas"
                                            data-target="#PendingRefundRequestFilter">
                                        <i class="fi fi-sr-settings-sliders"></i> {{ translate('Filter') }}
                                    </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive datatable-custom">
                <table
                    class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-start">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{translate('SL')}}</th>
                        <th class="text-center">{{translate('refund_id')}}</th>
                        <th>{{translate('order_ID')}} </th>
                        <th>{{translate('product_Info')}}</th>
                        <th>{{translate('customer_Info')}}</th>
                        <th class="text-end">{{ translate('total_amount') }}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($refundList as $key=>$refund)
                        @php
                            $isProductUnavailable = $refund->product === null;
                            $productDetails = $refund?->orderDetails?->product_details
                                ? json_decode($refund->orderDetails->product_details, true)
                                : null;
                        @endphp
                        <tr>
                            <td> {{$refundList->firstItem()+$key}}</td>
                            <td class="text-center">

                                <a class="title-color hover-c1"
                                   href="{{route('vendor.refund.details',['id'=>$refund['id']])}}">
                                    {{$refund['id']}}
                                </a>
                            </td>
                            <td>
                                <a class="title-color hover-c1"
                                   href="{{route('vendor.orders.details',[$refund->order_id])}}">
                                    {{$refund->order_id}}
                                </a>
                            </td>
                            <td>
                                <div class="d-flex refund-title flex-wrap gap-2 {{ $isProductUnavailable ? 'pe-none opacity-50 cus-disabled' : '' }}"
                                     data-toggle="tooltip" data-placement="top"
                                     title="{{ $isProductUnavailable ? translate('Product_has_been_deleted') : '' }}">
                                    @if (!$isProductUnavailable)
                                        <a href="{{ route('vendor.products.view', [$refund->product->id]) }}">
                                            <img src="{{ getStorageImages(path: $refund->product->thumbnail_full_url, type: 'backend-product') }}"
                                                 class="avatar border" alt="{{ $refund->product->name }}">
                                        </a>
                                        <div class="d-flex flex-column gap-1">
                                            <a href="{{ route('vendor.products.view', [$refund->product->id]) }}"
                                               class="title-color font-weight-bold hover-c1 line--limit-1 max-w-280 min-w-100">
                                                {{ Str::limit($refund->product->name, 35) }}
                                            </a>
                                            <span class="fs-12">{{ translate('qty') }} : {{ $refund?->orderDetails?->qty ?? 0 }}</span>
                                        </div>
                                    @else
                                        <img src="{{ getStorageImages(path: '', type: 'backend-product') }}"
                                             class="avatar border">
                                        <div class="d-flex flex-column gap-1">
                                            <span
                                               class="title-color font-weight-bold hover-c1 line--limit-1 max-w-280 min-w-100">
                                                {{ Str::limit($productDetails['name'] ?? translate('product_name_not_found'), 35) }}
                                            </span>
                                            <span class="fs-12">{{ translate('qty') }} : {{ $refund?->orderDetails?->qty ?? 0 }}</span>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if ($refund->customer !=null)
                                    <div class="d-flex flex-column gap-1">
                                    <span class="title-color font-weight-bold" style="text-decoration: none;">
                                        {{$refund->customer->f_name.' '.$refund->customer->l_name}}
                                    </span>
                                        @if($refund->customer->phone)
                                            <span class="title-color fs-12">
                                                {{$refund->customer?->phone}}
                                            </span>
                                                @else
                                            <span class="title-color fs-12">
                                                {{$refund->customer?->email}}
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <a href="javascript:" class="title-color hover-c1">
                                        {{translate('customer_not_found')}}
                                    </a>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1 text-end">
                                    <div>
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $refund->amount), currencyCode: getCurrencyCode()) }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a class="btn btn-outline--primary icon-btn"
                                       href="{{route('vendor.refund.details',['id'=>$refund['id']])}}">
                                       <span data-toggle="tooltip" data-placement="top" title="View">
                                           <i class="fi fi-sr-eye d-flex"></i>
                                       </span>
                                    </a>
                                    @if($refund['status'] != 'refunded')
                                        @if($refund['status'] != 'rejected')
                                            <button class="btn btn-outline-danger icon-btn"
                                                    data-toggle="modal"
                                                    data-target="#rejectModal-{{$refund['id']}}">
                                                <span data-toggle="tooltip" data-placement="top" title="Reject">
                                                    <i class="fi fi-rr-cross fs-12 d-flex"></i>
                                                </span>
                                            </button>
                                        @endif
                                        @if($refund['status'] != 'approved')
                                            <button class="btn btn-outline-success icon-btn"
                                                    data-toggle="modal"
                                                    data-target="#approveModal-{{$refund['id']}}">
                                                <span data-toggle="tooltip" data-placement="top" title="Approve">
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
            @if(count($refundList)==0)
                @include('layouts.vendor.partials._empty-state',['text'=>'no_refund_request_found'],['image'=>'default'])
            @endif
        </div>
    </div>
    @include('vendor-views.refund.partials._filter-offcanvas')

    @if(count($refundList ?? []) > 0)
        @foreach($refundList as $refund)
            @include('vendor-views.refund.partials._approval-modal')
            @include('vendor-views.refund.partials._reject-modal')
        @endforeach
    @endif
@endsection
