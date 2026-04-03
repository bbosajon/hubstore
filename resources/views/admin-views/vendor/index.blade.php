@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin.app')

@section('title', translate('vendor_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png')}}" alt="">
                {{translate('vendor_List')}}
                <span class="badge badge-info text-bg-info">{{ $vendors->total() }}</span>
            </h2>
        </div>

        <div class="card">
            <div class="px-3 py-4">
                <div class="d-flex justify-content-between gap-10 flex-wrap align-items-center mb-4">
                    <div class="">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                       placeholder="{{translate('search_by_shop_name_or_vendor_name_or_phone_or_email')}}" aria-label="Search orders" value="{{ request('searchValue') }}">
                                <div class="input-group-append search-submit">
                                    <button type="submit">
                                        <i class="fi fi-rr-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex justify-content-end gap-3">
                        <a type="button" class="btn btn-outline-primary" href="{{route('admin.vendors.export',['searchValue' => request('searchValue')])}}">
                            <i class="fi fi-sr-inbox-in"></i>
                            <span class="fs-12">{{ translate('export') }}</span>
                        </a>

                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-sm-end flex-grow-1">
                            <div class="dropdown">
                                <div class="position-relative">
                                    @if(!empty(request('sort_by')))
                                        <div class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2" style="--size: 14px;"></div>
                                    @endif
                                </div>
                                <button type="button" class="btn {{ !empty(request('sort_by')) ? 'btn-primary' : 'btn-outline-primary' }}" data-bs-toggle="dropdown">
                                    <i class="fi fi-sr-sort-alt"></i>
                                    <span class="fs-12">{{ translate('Sorting') }}</span>
                                    <i class="fi fi-rr-angle-small-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.vendors.vendor-list', ['sort_by' => 'latest', 'searchValue' => request('searchValue')]) }}">
                                            <span class="text-success pt-1">
                                                <input type="radio" name="sort_by" {{ empty(request('sort_by')) || request('sort_by') == 'latest' ? 'checked' : '' }}>
                                            </span>
                                            {{ translate('Default') }} ({{ translate('Recent created') }})
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.vendors.vendor-list', ['sort_by' => 'oldest', 'searchValue' => request('searchValue')]) }}">
                                            <span class="text-success pt-1">
                                                <input type="radio" name="sort_by" {{ request('sort_by') == 'oldest' ? 'checked' : '' }}>
                                            </span>
                                            {{ translate('Show Older First') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.vendors.vendor-list', ['sort_by' => 'most-favorite', 'searchValue' => request('searchValue')]) }}">
                                            <span class="text-info pt-1">
                                                <input type="radio" name="sort_by" {{ request('sort_by') == 'most-favorite' ? 'checked' : '' }}>
                                            </span>
                                            {{ translate('Most Popular Store') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.vendors.vendor-list', ['sort_by' => 'orders_count', 'searchValue' => request('searchValue')]) }}">
                                            <span class="text-info pt-1">
                                                <input type="radio" name="sort_by" {{ request('sort_by') == 'orders_count' ? 'checked' : '' }}>
                                            </span>
                                            {{ translate('Top Selling Store') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <a href="{{route('admin.vendors.add')}}" type="button" class="btn btn-primary text-nowrap">
                            <i class="fi fi-rr-plus-small"></i>
                            {{translate('add_New_Vendor')}}
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('shop_name')}}</th>
                                <th>{{translate('vendor_name')}}</th>
                                <th>{{translate('contact_info')}}</th>
                                <th>{{translate('status')}}</th>
                                <th class="text-center">{{translate('total_products')}}</th>
                                <th class="text-center">{{translate('total_orders')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($vendors as $key=>$seller)
                            <tr>
                                <td>{{$vendors->firstItem()+$key}}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-10 w-max-content">
                                        <img width="50"
                                        class="avatar rounded-circle object-fit-cover" src="{{ getStorageImages(path: $seller?->shop?->image_full_url, type: 'backend-basic') }}"
                                            alt="">
                                        <div>
                                            <a class="text-dark text-hover-primary" href="{{ route('admin.vendors.view', ['id' => $seller->id]) }}">{{ $seller->shop ? Str::limit($seller->shop->name, 20) : translate('shop_not_found')}}</a>
                                            <span class="text-danger fs-12">
                                                @if(checkVendorAbility(type: 'vendor', status: 'temporary_close', vendor: $seller?->shop))
                                                    <br>
                                                    {{ translate('temporary_closed') }}
                                                @elseif(checkVendorAbility(type: 'vendor', status: 'vacation_status', vendor: $seller?->shop))
                                                    <br>
                                                    {{ translate('On_Vacation') }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a title="{{translate('view')}}"
                                        class="text-dark text-hover-primary"
                                        href="{{route('admin.vendors.view',$seller->id)}}">
                                        {{$seller->f_name}} {{$seller->l_name}}
                                    </a>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <strong><a class="text-dark text-hover-primary" href="mailto:{{$seller->email}}">{{$seller->email}}</a></strong>
                                    </div>
                                    <a class="text-dark text-hover-primary" href="tel:{{$seller->phone}}">{{$seller->phone}}</a>
                                </td>
                                <td>
                                    {!! $seller->status=='approved'?'<label class="badge badge-success text-bg-success">'.translate('active').'</label>':'<label class="badge badge-danger text-bg-danger">'.translate('inactive').'</label>' !!}
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.vendors.view', ['id'=>$seller['id'], 'tab'=>'product']) }}"
                                        class="badge badge-info text-bg-info">
                                        {{$seller->product->count()}}
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.vendors.view',['id'=>$seller['id'], 'tab'=>'order']) }}"
                                        class="badge badge-info text-bg-info">
                                        {{ $seller->orders->where('seller_is', 'seller')->where('order_type', 'default_type')->count() }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a title="{{translate('view')}}"
                                            class="btn btn-outline-info icon-btn"
                                            href="{{route('admin.vendors.view',$seller->id)}}">
                                            <i class="fi fi-rr-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-center justify-content-md-end">
                        {!! $vendors->links() !!}
                    </div>
                </div>
                @if(count($vendors)==0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_vendor_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
@endsection
