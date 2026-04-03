@extends('layouts.admin.app')

@section('title', translate('product_List'))

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/inhouse-product-list.png') }}" alt="">
                @if ($type == 'in_house')
                    {{ translate('in_House_Product_List') }}
                @elseif($type == 'seller')
                    {{ translate('vendor_Product_List') }}
                @endif
                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">{{ $products->total() }}</span>
            </h2>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div class="min-w-300 min-w-100-mobile">
                                <form action="{{ url()->current() }}" method="get">
                                    <input type="hidden" value="{{ request('request_status') }}" name="request_status">
                                    <input type="hidden" value="{{ request('status') }}" name="status">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{ translate('search_by_Product_Name') }}"
                                               aria-label="Search orders" value="{{ request('searchValue') }}">
                                        <div class="input-group-append search-submit">
                                            <button type="submit">
                                                <i class="fi fi-rr-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="d-flex flex-wrap gap-3 justify-content-lg-end">
                                <div class="dropdown">
                                    <a type="button" class="btn btn-outline-primary"
                                       href="{{ route('admin.products.export-excel', [
                                                'type' => request('type'),
                                                'request_status' => request('request_status'),
                                                'searchValue' => request('searchValue'),
                                                'seller_id' => request('seller_id'),
                                                'filter_sort_by' => request('filter_sort_by'),
                                                'filter_product_types' => request('filter_product_types'),
                                                'product_status' => request('product_status'),
                                                'filter_brand_ids' => request('filter_brand_ids'),
                                                'filter_category_ids' => request('filter_category_ids'),
                                            ]) }}">
                                        <i class="fi fi-sr-inbox-in"></i>
                                        <span class="fs-12">{{ translate('export') }}</span>
                                    </a>
                                </div>

                                <div class="position-relative">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas"
                                           data-bs-target="#offcanvasProductFilter">
                                        <i class="fi fi-sr-settings-sliders d-flex"></i> {{ translate('Filter') }}
                                    </button>
                                    @if(!empty(request('filter_sort_by')) || !empty(request('filter_product_types')) || !empty(request('product_status')) || !empty(request('filter_shop_ids')) || !empty(request('filter_brand_ids')) || !empty(request('filter_category_ids')))
                                        <div
                                            class="position-absolute top-n1 inset-inline-end-n1 btn-circle bg-danger border border-white border-2"
                                            style="--size: 12px;"></div>
                                    @endif
                                </div>

                                @if ($type == 'in_house')
                                    <a href="{{ route('admin.products.add') }}" class="btn btn-primary">
                                        <i class="fi fi-sr-add"></i>
                                        <span class="text">{{ translate('Add_New_Product') }}</span>
                                    </a>
                                @endif
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
                                <th class="text-center">{{ translate('product Type') }}</th>
                                <th class="text-center">{{ translate('unit_price') }}</th>
                                <th class="text-center">{{ translate('stock') }}</th>
                                @if ($productWiseTax)
                                    <th class="text-center">{{ translate('Vat/Tax') }}</th>
                                @endif
                                <th>
                                    <div class="d-flex justify-content-center">
                                        <div>
                                            <div class="d-flex gap-2">
                                                <span>{{ translate('show_as_featured') }} </span>
                                                <span class="tooltip-icon" data-bs-toggle="tooltip"
                                                      data-bs-placement="top"
                                                      aria-label="{{ translate('Highlight_this_product_on_the_homepage_by_marking_it_as_featured') }}"
                                                      data-bs-title="{{ translate('Highlight_this_product_on_the_homepage_by_marking_it_as_featured') }}">
                                                        <i class="fi fi-sr-info"></i>
                                                    </span>
                                            </div>
                                            <div>{{ translate('on_homepage') }}</div>
                                        </div>
                                    </div>
                                </th>
                                <th class="text-center">{{ translate('status') }}

                                </th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($products as $key => $product)
                                <tr>
                                    <th scope="row">{{ $products->firstItem() + $key }}</th>
                                    <td>
                                        <a href="{{ route('admin.products.view', ['addedBy' => $product['added_by'] == 'seller' ? 'vendor' : 'in-house', 'id' => $product['id']]) }}"
                                           class="media align-items-center gap-2">
                                            <img
                                                src="{{ getStorageImages(path: $product->thumbnail_full_url, type: 'backend-product') }}"
                                                class="avatar border object-fit-cover" alt="">
                                            <div>
                                                <div
                                                    class="d-flex gap-2 align-items-center lh-1 w-max-content text-wrap line-1 max-w-300 min-w-130 text-dark text-hover-primary">
                                                    <div class="media-body text-dark line-1 text-hover-primary"
                                                         data-bs-toggle="tooltip" title="{{ $product['name'] }}">
                                                        {{ Str::limit($product['name'], 20) }}
                                                    </div>
                                                    @if ($product?->clearanceSale)
                                                        <span class="text-secondary" data-bs-toggle="tooltip"
                                                              title="{{ translate('Clearance_Sale') }}">
                                                                <i class="fi fi-sr-bahai"></i>
                                                            </span>
                                                    @endif
                                                </div>
                                                <div class="d-flex gap-2 align-items-center lh-1 mt-2">
                                                    <div class="text-body">
                                                        {{ translate('Id') }} # {{$product['id']}}
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ translate(str_replace('_', ' ', $product['product_type'])) }}
                                    </td>
                                    <td class="text-center">
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $product['unit_price']), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2 align-items-center lh-1">
                                            @if ($product['product_type'] === 'physical')
                                                <span>{{ $product->current_stock }}</span>
                                                @if ($product->current_stock <= 0)
                                                    <span class="text-danger-dark fs-18"
                                                          data-bs-toggle="tooltip"
                                                          title="{{ translate('Out_of_Stock') }}">
                                                        <i class="fi fi-sr-exclamation"></i>
                                                    </span>
                                                @elseif ($product->current_stock <= 20)
                                                    <span class="text-warning-dark fs-18"
                                                          data-bs-toggle="tooltip"
                                                          title="{{ translate('Low_Stock') }}">
                                                        <i class="fi fi-sr-exclamation"></i>
                                                    </span>
                                                @endif
                                            @else
                                                <span>-</span>
                                            @endif
                                        </div>
                                    </td>

                                    @if ($productWiseTax)
                                        <td class="text-center">
                                                <span class="">
                                                    @forelse ($product?->taxVats as $key => $taxVat)
                                                        <span>{{ $taxVat?->tax?->name }} :
                                                            <span class="font-bold">
                                                                ({{ $taxVat?->tax?->tax_rate }}%)
                                                            </span>
                                                        </span>
                                                        <br>
                                                    @empty
                                                        <span>{{ translate('N/A') }}</span>
                                                    @endforelse
                                                </span>
                                        </td>
                                    @endif
                                    @php
                                        $isDisabled = request()->has('request_status') && request('request_status') === '0' || request('request_status') === '2';
                                    @endphp
                                    <td class="text-center">
                                        <div class="" data-bs-toggle="{{ $isDisabled ? 'tooltip' : '' }}" title="{{ $isDisabled ? translate('Non approved products cannot be featured') : '' }}">
                                            <input type="checkbox"
                                                   class="product-status-checkbox form-check-input checkbox--input checkbox--input_lg"
                                                   data-id="{{ $product['id'] }}"
                                                   data-action="{{ route('admin.products.featured-status') }}"
                                                {{ $product['featured'] == 1 ? 'checked' : '' }}
                                                {{ $isDisabled ? 'disabled' : '' }}>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <form action="{{ route('admin.products.status-update') }}" method="post"
                                              id="product-status{{ $product['id'] }}-form"
                                              class="admin-product-status-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $product['id'] }}">
                                            <label class="switcher mx-auto"
                                                   for="products-status-update-{{ $product['id'] }}">
                                                <input class="switcher_input custom-modal-plugin" type="checkbox"
                                                       value="1" name="status"
                                                       id="products-status-update-{{ $product['id'] }}"
                                                       {{ $product['status'] == 1 ? 'checked' : '' }}
                                                       data-modal-type="input-change-form"
                                                       data-modal-form="#product-status{{ $product['id'] }}-form"
                                                       data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-on.png') }}"
                                                       data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/product-status-off.png') }}"
                                                       data-on-title="{{ translate('Want_to_Turn_ON') . ' ' . str_replace("'", '`', $product['name']) . ' ' . translate('status') }}"
                                                       data-off-title="{{ translate('Want_to_Turn_OFF') . ' ' . str_replace("'", '`', $product['name']) . ' ' . translate('status') }}"
                                                       data-on-message="<p>{{ translate('if_enabled_this_product_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                       data-off-message="<p>{{ translate('if_disabled_this_product_will_be_hidden_from_the_website_and_customer_app') }}</p>">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn top01 btn-outline-warning btn-outline-warning-dark icon-btn"
                                               title="{{ translate('barcode') }}"
                                               href="{{ route('admin.products.barcode', [$product['id']]) }}">
                                                <i class="fi fi-sr-barcode"></i>
                                            </a>
                                            <a class="btn btn-outline-success top01 btn-outline-success-dark icon-btn"
                                               title="View"
                                               href="{{ route('admin.products.view', ['addedBy' => $product['added_by'] == 'seller' ? 'vendor' : 'in-house', 'id' => $product['id']]) }}">
                                                <i class="fi fi-sr-eye"></i>
                                            </a>
                                            <a class="btn btn-outline-info top01 icon-btn"
                                               title="{{ translate('edit') }}"
                                               href="{{ route('admin.products.update', [$product['id']]) }}">
                                                <i class="fi fi-sr-pencil"></i>
                                            </a>
                                            <span class="btn btn-outline-danger top01 icon-btn delete-data"
                                                  title="{{ translate('delete') }}"
                                                  data-id="product-{{ $product['id'] }}">
                                                    <i class="fi fi-rr-trash"></i>
                                                </span>
                                        </div>
                                        <form action="{{ route('admin.products.delete', [$product['id']]) }}"
                                              method="post" id="product-{{ $product['id'] }}">
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

                    @if (count($products) == 0)
                        @include(
                            'layouts.admin.partials._empty-state',
                            ['text' => 'no_product_found'],
                            ['image' => 'default']
                        )
                    @endif
                </div>
            </div>
        </div>
    </div>
    <span id="message-select-word" data-text="{{ translate('select') }}"></span>

    @include('admin-views.product.partials.offcanvas._filter-offcanvas')
@endsection

