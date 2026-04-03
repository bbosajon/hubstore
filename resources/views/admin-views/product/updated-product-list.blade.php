@extends('layouts.admin.app')

@section('title', translate('updated_product_list'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 text-capitalize mb-1 d-flex gap-2 align-items-center">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                {{ translate('update_product') }}
            </h2>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center">

                            <h3 class="mb-0 d-flex gap-2 align-items-center">
                                {{ translate('product_table') }}
                                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">
                                {{ $products->total() }}
                            </span>
                            </h3>
                            <div class="d-flex align-items-center gap-3">
                                <div class="flex-grow-1 max-w-300 min-w-100-mobile">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="searchValue"
                                                   class="form-control"
                                                   placeholder="{{ translate('search_Product_Name') }}"
                                                   value="{{ $searchValue }}" required>
                                            <div class="input-group-append search-submit">
                                                <button type="submit">
                                                    <i class="fi fi-rr-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="position-relative">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasProductFilter">
                                        <i class="fi fi-sr-settings-sliders d-flex"></i> {{ translate('Filter') }}
                                    </button>
                                    @if(!empty(request('filter_sort_by')) || !empty(request('filter_product_types')) || !empty(request('product_status')) || !empty(request('filter_shop_ids')) || !empty(request('filter_brand_ids')) || !empty(request('filter_category_ids')))
                                        <div class="position-absolute top-n1 inset-inline-end-n1 btn-circle bg-danger border border-white border-2"
                                             style="--size: 12px;"></div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered align-middle">
                            <thead class="text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('product Name') }}</th>
                                <th>{{ translate('previous_shipping_cost') }}</th>
                                <th>{{ translate('new_shipping_cost') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $key=>$product)
                                <tr>
                                    <th scope="row">{{ $products->firstItem()+$key}}</th>
                                    <td>
                                        <a href="{{route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']]) }}"
                                           class="text-dark text-hover-primary">
                                            {{ Str::limit($product['name'],20) }}
                                        </a>
                                    </td>
                                    <td>
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['shipping_cost']), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td>
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['temp_shipping_cost']), currencyCode: getCurrencyCode()) }}
                                    </td>

                                    <td>
                                        <div class="d-flex gap-10 align-items-center justify-content-center">
                                            <button class="btn btn-primary btn-sm update-status"
                                                    data-id="{{ $product['id'] }}"
                                                    data-message ="{{translate('want_to_approve_this_update_request').'?'}}"
                                                    data-status="1">
                                                {{ translate('approved') }}
                                            </button>
                                            <button class="btn btn-danger btn-sm update-status"
                                                    data-id="{{ $product['id'] }}"
                                                    data-message ="{{translate('want_to_deny_this_update_request').'?'}}"
                                                    data-status="2">
                                                {{ translate('denied') }}
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {{ $products->links() }}
                        </div>
                    </div>

                    @if(count($products)==0)
                        @include('layouts.admin.partials._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
<span id="get-update-status-route" data-action="{{ route('admin.products.updated-shipping') }}"></span>
    @include('admin-views.product.partials.offcanvas._filter-offcanvas')
@endsection
