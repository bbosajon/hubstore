@extends('layouts.admin.app')

@section('title', translate('restock_product_List'))

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/inhouse-product-list.png') }}" alt="">
                {{ translate('Request_Restock_List') }}
                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">{{ $totalRestockProducts }}</span>
            </h2>
        </div>

        <div class="mt-20">
            <div class="card">
                <div class="px-3 py-4 d-flex justify-content-between align-items-center gap-20 flex-wrap">
                    <div class="min-w-300 min-w-100-mobile">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input type="hidden" name="restock_date" value="{{ request('restock_date') }}">
                                <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                <input type="hidden" name="sub_category_id" value="{{ request('sub_category_id') }}">
                                <input type="hidden" name="brand_id" value="{{ request('brand_id') }}">
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                    placeholder="{{ translate('search_by_Product_Name') }}" aria-label="Search orders"
                                    value="{{ request('searchValue') }}">
                                <div class="input-group-append search-submit">
                                    <button type="submit">
                                        <i class="fi fi-rr-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-sm-end flex-grow-1">
                        <div class="dropdown">
                            <a type="button" class="btn btn-outline-primary"
                                href="{{ route('admin.products.restock-export', ['restock_date' => request('restock_date'), 'brand_id' => request('brand_id'), 'category_id' => request('category_id'), 'sub_category_id' => request('sub_category_id'), 'searchValue' => request('searchValue')]) }}">
                                <i class="fi fi-sr-inbox-in"></i>
                                <span class="fs-12">{{ translate('export') }}</span>
                            </a>
                        </div>
                        <div class="position-relative">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasRestockFilter">
                                <i class="fi fi-sr-settings-sliders d-flex"></i> {{ translate('Filter') }}
                            </button>
                            @if(!empty(request('filter_sort_by')) || !empty(request('filter_product_types')) || !empty(request('product_status')) || !empty(request('filter_shop_ids')) || !empty(request('filter_brand_ids')) || !empty(request('filter_category_ids')))
                                <div
                                    class="position-absolute top-n1 inset-inline-end-n1 btn-circle bg-danger border border-white border-2"
                                    style="--size: 12px;"></div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-hover table-borderless table-thead-bordered align-middle">
                        <thead class="text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('product_name') }}</th>
                                <th class="text-center">{{ translate('selling_price') }}</th>
                                <th class="text-center">{{ translate('last_request_date') }}</th>
                                <th class="text-center">{{ translate('number_of_request') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($restockProducts as $key => $restockProduct)
                                <tr>
                                    <th scope="row"> {{ $restockProducts->firstItem() + $key }}</th>
                                    <td>
                                        <a href="{{ route('admin.products.view', ['addedBy' => $restockProduct->product['added_by'] == 'seller' ? 'vendor' : 'in-house', 'id' => $restockProduct->product['id'] ?? 0]) }}"
                                            class="media align-items-center gap-2">
                                            <img src="{{ getStorageImages(path: $restockProduct?->product?->thumbnail_full_url, type: 'backend-product') }}"
                                                class="avatar border object-fit-cover" alt="">
                                            <span class="media-body text-dark text-primary-hover">
                                                {{ Str::limit($restockProduct->product['name'] ?? '', 20) }}
                                                <p class="small fw-bold m-0">
                                                    @if ($restockProduct['variant'])
                                                        {{ translate('Variant:') . ' ' . $restockProduct['variant'] }}
                                                    @endif
                                                </p>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $restockProduct->product['unit_price'] ?? 0), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $restockProduct->updated_at->format('d F Y, h:i A') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $restockProduct?->restockProductCustomers?->count() ?? 0 }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline-info icon-btn" title="View"
                                                href="{{ route('admin.products.view', ['addedBy' => $restockProduct->product['added_by'] == 'seller' ? 'vendor' : 'in-house', 'id' => $restockProduct->product['id'] ?? 0]) }}">
                                                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/icons/restock_view.svg') }}"
                                                    alt="">
                                            </a>
                                            <a class="btn btn-outline-primary icon-btn action-update-product-quantity"
                                                title="{{ translate('edit') }}"
                                                id="{{ $restockProduct->product['id'] }}"
                                                data-url="{{ route('admin.products.get-variations', ['id' => $restockProduct->product['id'], 'restock_id' => $restockProduct->id]) }}"
                                                data-bs-target="#update-stock">
                                                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/icons/restock_update.svg') }}"
                                                    alt="">
                                            </a>
                                            <span class="btn btn-outline-danger icon-btn delete-data"
                                                title="{{ translate('delete') }}"
                                                data-id="product-{{ $restockProduct->id }}">
                                                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/icons/restock_delete.svg') }}"
                                                    alt="">
                                            </span>
                                        </div>
                                        <form action="{{ route('admin.products.restock-delete', [$restockProduct->id]) }}"
                                            method="post" id="product-{{ $restockProduct->id }}">
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
                        {{ $restockProducts->links() }}
                    </div>
                </div>

                @if (count($restockProducts) == 0)
                    @include(
                        'layouts.admin.partials._empty-state',
                        ['text' => 'no_product_found'],
                        ['image' => 'default']
                    )
                @endif
            </div>
        </div>
    </div>
    <span id="message-select-word" data-text="{{ translate('select') }}"></span>
    <div class="modal fade update-stock-modal restock-stock-update" id="update-stock" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.products.update-quantity') }}" method="post" class="odal-body p-20">
                    @csrf
                    <div class="rest-part-content"></div>
                    <div class="d-flex justify-content-end gap-10 flex-wrap align-items-center">
                        <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal" aria-label="Close">
                            {{ translate('close') }}
                        </button>
                        <button class="btn btn-primary px-4" type="submit">
                            {{ translate('update') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin-views.product.partials.offcanvas._restock-list-filter-offcanvas')
@endsection

@push('script')
    <script>
        $(document).ready(function () {

            function toggleCustomDate(wrapper) {
                let selectValue = wrapper.find('.date-type-select').val();

                if (selectValue === 'custom') {
                    wrapper.find('.custom-date-div').slideDown(200);
                } else {
                    wrapper.find('.custom-date-div').slideUp(200);
                }
            }

            $(document).on('change', '.date-type-select', function () {
                let wrapper = $(this).closest('.date-filter-wrapper');
                toggleCustomDate(wrapper);
            });

            $('.date-filter-wrapper').each(function () {
                toggleCustomDate($(this));
            });

        });
    </script>
@endpush
