@extends('layouts.admin.app')

@section('title', translate('stock_limit_products'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-flex flex-column gap-1">
            <h2 class="h1 text-capitalize d-flex gap-2 align-items-center">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png') }}" class="mb-1 mr-1" alt="">
                {{ translate('limited_Stocked_Products_List') }}
                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">
                    {{ $products->total() }}
                </span>
            </h2>
            <p class="d-flex mb-0">
                {{ translate('the_products_are_shown_in_this_list,_which_quantity_is_below') }} {{ $stockLimit }}
            </p>
        </div>
        <div class="row mt-30">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-20">
                        <div class="d-flex justify-content-between align-items-center gap-20 flex-wrap">
                            <div class="flex-grow-1 max-w-300 min-w-100-mobile">
                                <form action="{{ url()->current() }}" method="get">
                                    @csrf
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{ translate('search_by_Product_Name') }}"
                                               aria-label="Search orders"
                                               value="{{ $searchValue }}" required>
                                        <input type="hidden" value="{{ $status }}" name="status">
                                        <div class="input-group-append search-submit">
                                            <button type="submit">
                                                <i class="fi fi-rr-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="select-wrapper">
                                <select name="qty_order_sort" class="form-select action-select-onchange-get-view"
                                        data-url-prefix="{{ route('admin.products.stock-limit-list',['in_house', '']) }}/?sortOrderQty=">
                                    <option value="default" {{ $sortOrderQty== "default"?'selected':''}}>
                                        {{ translate('default') }}
                                    </option>
                                    <option value="quantity_asc" {{ $sortOrderQty== "quantity_asc"?'selected':''}}>
                                        {{ translate('inventory_quantity(low_to_high)') }}
                                    </option>
                                    <option value="quantity_desc" {{ $sortOrderQty== "quantity_desc"?'selected':''}}>
                                        {{ translate('inventory_quantity(high_to_low)') }}
                                    </option>
                                    <option value="order_asc" {{ $sortOrderQty== "order_asc"?'selected':''}}>
                                        {{ translate('order_volume(low_to_high)') }}
                                    </option>
                                    <option value="order_desc" {{ $sortOrderQty== "order_desc"?'selected':''}}>
                                        {{ translate('order_volume(high_to_low)') }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless align-middle">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('product_Name') }}</th>
                                        <th class="text-center">{{ translate('unit_price') }}</th>
                                        <th class="text-center">{{ translate('quantity') }}</th>
                                        <th class="text-center">{{ translate('orders') }}</th>
                                        <th class="text-center">{{ translate('active_status') }}</th>
                                        <th class="text-center">{{ translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $key=>$product)
                                    <tr>
                                        <th scope="row">{{ $products->firstItem()+$key}}</th>
                                        <td>
                                            <a href="{{route('admin.products.view',['addedBy'=>($product['added_by']=='seller'?'vendor' : 'in-house'),'id'=>$product['id']]) }}"
                                            class="media align-items-center gap-2">
                                                <img src="{{ getStorageImages(path:$product->thumbnail_full_url,type: 'backend-product')}}"
                                                    class="avatar border object-fit-cover" alt="">
                                                <span class="media-body text-dark text-primary-hover">
                                                    {{ Str::limit($product['name'], 20) }}
                                                </span>
                                            </a>
                                        </td>
                                        <td class="text-center">
                                            {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['unit_price']), currencyCode: getCurrencyCode()) }}
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 product-quantity justify-content-center">
                                                <span class="lh-1">{{ $product['current_stock'] }}</span>

                                                <button class="btn p-0 border-0 fs-18 action-update-product-quantity"
                                                        id="{{ $product['id'] }}"
                                                        data-url="{{ route('admin.products.get-variations').'?id='.$product['id'] }}"
                                                        type="button"
                                                        data-bs-target="#update-quantity"
                                                        data-bs-toggle="modal"
                                                        title="{{ translate('update_quantity') }}">
                                                        <i class="fi fi-sr-add text-primary"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ ($product['order_details_count'])}}</td>

                                        <td class="text-center">
                                            @if($product->request_status != 2 )
                                                <form action="{{route('admin.products.status-update') }}" method="post"
                                                    id="product-status{{ $product['id'] }}-form"
                                                    class="no-reload-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $product['id']}}">
                                                    <label class="switcher mx-auto" for="product-status{{ $product['id'] }}">
                                                        <input
                                                            class="switcher_input custom-modal-plugin"
                                                            type="checkbox" value="1" name="status"
                                                            id="product-status{{ $product['id'] }}"
                                                            {{ $product['status'] == 1 ? 'checked' : '' }}
                                                            data-modal-type="input-change-form"
                                                            data-modal-form="#product-status{{ $product['id'] }}-form"
                                                            data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                            data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                            data-on-title="{{ translate('Want_to_Turn_ON').' '.$product['name'].' '.translate('status') }}"
                                                            data-off-title="{{ translate('Want_to_Turn_OFF').' '.$product['name'].' '.translate('status') }}"
                                                            data-on-message="<p>{{ translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                            data-off-message="<p>{{ translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app') }}</p>"
                                                            data-on-button-text="{{ translate('turn_on') }}"
                                                            data-off-button-text="{{ translate('turn_off') }}">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </form>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <a class="btn btn-outline-info icon-btn"
                                                title="{{ translate('barcode') }}"
                                                href="{{ route('admin.products.barcode', [$product['id']]) }}">
                                                    <i class="fi fi-sr-barcode"></i>
                                                </a>
                                                <a class="btn btn-outline-primary icon-btn"
                                                title="{{ translate('edit') }}"
                                                href="{{route('admin.products.update',[$product['id']]) }}">
                                                    <i class="fi fi-sr-pencil"></i>
                                                </a>
                                                <span class="btn btn-outline-danger icon-btn delete-data"
                                                    title="{{ translate('delete') }}"
                                                    data-id="product-{{ $product['id']}}">
                                                    <i class="fi fi-rr-trash"></i>
                                                </span>
                                            </div>
                                            <form action="{{ route('admin.products.delete', [$product['id']]) }}"
                                                method="post" id="product-{{ $product['id']}}">
                                                @csrf @method('delete')
                                            </form>
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
    </div>

    <div class="modal fade update-stock-modal" id="update-quantity" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.products.update-quantity') }}" method="post" class="modal-body p-20">
                    @csrf
                    <div class="rest-part-content"></div>
                    <div class="d-flex justify-content-end gap-10 flex-wrap align-items-center">
                        <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal" aria-label="Close">
                            {{ translate('close') }}
                        </button>
                        <button class="btn btn-primary px-4" type="submit">
                            {{ translate('submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
