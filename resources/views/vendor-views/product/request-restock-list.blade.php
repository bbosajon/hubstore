@extends('layouts.vendor.app')

@section('title', translate('Restock_Product_List'))

@section('content')
    <div class="content container-fluid">

        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/inhouse-product-list.png') }}" alt="">
                {{ translate('Request_Restock_List') }}
                <span class="badge badge-soft-dark radius-50 fz-14 ml-1">{{ $totalRestockProducts }}</span>
            </h2>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header gap-3 align-items-center justify-content-between flex-wrap">
                        <form action="{{ url()->current() }}" method="GET">
                            <input type="hidden" name="restock_date" value="{{request('restock_date')}}">
                            <input type="hidden" name="category_id" value="{{request('category_id')}}">
                            <input type="hidden" name="sub_category_id" value="{{request('sub_category_id')}}">
                            <input type="hidden" name="brand_id" value="{{request('brand_id')}}">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                       placeholder="{{ translate('search_by_Product_Name')}}"  aria-label="Search orders" value="{{ request('searchValue') }}">
                                <button type="submit" class="btn btn--primary">{{ translate('search')}}</button>
                            </div>
                        </form>

                        <div class="d-flex gap-3 align-items-stretch flex-wrap">
                            <div class="dropdown">
                                <a type="button" class="btn btn-outline--primary text-nowrap h-100" href="{{route('vendor.products.restock-export', ['restock_date' => request('restock_date'),'brand_id' => request('brand_id'), 'category_id' => request('category_id'), 'sub_category_id' => request('sub_category_id'),  'searchValue' => request('searchValue')])}}">
                                    <img width="14" src="{{dynamicAsset(path: 'public/assets/back-end/img/excel.png')}}" class="excel" alt="">
                                    <span class="ps-2">{{ translate('export') }}</span>
                                </a>
                            </div>
                            <div class="position-relative">
                                @if(!empty(request('filter_sort_by')) || !empty(request('filter_product_types')) || !empty(request('product_status')) || !empty(request('filter_shop_ids')) || !empty(request('filter_brand_ids')) || !empty(request('filter_category_ids')))
                                    <div class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2 z-2" style="--size: 12px;"></div>
                                @endif
                                <button type="button"
                                        @if(!empty(request('filter_sort_by')) || !empty(request('filter_product_types')) || !empty(request('product_status')) || !empty(request('filter_shop_ids')) || !empty(request('filter_brand_ids')) || !empty(request('filter_category_ids')))
                                            class="btn btn--primary px-4 h-100"
                                        @else
                                            class="btn btn-outline--primary px-4 h-100"
                                        @endif
                                        data-toggle="offcanvas" data-target="#offcanvasRestockFilter">
                                    <i class="fi fi-sr-settings-sliders"></i>
                                    {{ translate('Filter') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 text-start">
                            <thead class="thead-light thead-50 text-capitalize">
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
                            @foreach($restockProducts as $key=>$restockProduct)
                                <tr>
                                 <th scope="row">{{ $restockProducts->firstItem() + $key }}</th>
                                    <td>
                                        <a href="{{ route('vendor.products.view',['id'=>$restockProduct->product['id'] ?? 0]) }}"
                                           class="media align-items-center gap-2">
                                            <img src="{{ getStorageImages(path: $restockProduct?->product?->thumbnail_full_url, type: 'backend-product') }}"
                                                 class="avatar border" alt="">
                                            <span class="media-body title-color hover-c1">
                                                {{ Str::limit($restockProduct->product['name'] ?? '', 20) }}
                                                @if($restockProduct['variant'])
                                                    <p class="small font-weight-bold m-0">{{ translate('Variant:') }} {{ $restockProduct['variant'] }}</p>
                                                @endif
                                            </span>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        {{setCurrencySymbol(amount: usdToDefaultCurrency(amount: $restockProduct->product['unit_price'] ?? 0), currencyCode: getCurrencyCode()) }}
                                    </td>
                                    <td class="text-center">
                                        {{ $restockProduct->updated_at->format('d F Y, h:i A') }}
                                    </td>
                                    <td class="text-center">
                                        {{ $restockProduct?->restockProductCustomers?->count() ?? 0 }}
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a class="btn btn-outline-info btn-sm square-btn" title="View"
                                               href="{{ route('vendor.products.view',['id'=>$restockProduct->product['id'] ?? 0]) }}">
                                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/restock_view.svg') }}" alt="">
                                            </a>
                                            <a class="btn btn-outline--primary btn-sm square-btn action-update-product-quantity"
                                               title="{{ translate('edit') }}"
                                               id="{{ $restockProduct->product['id'] }}"
                                               data-url="{{ route('vendor.products.get-variations', ['id'=> $restockProduct->product['id'], 'restock_id' => $restockProduct->id]) }}"
                                               data-target="#update-stock">
                                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/restock_update.svg') }}" alt="">
                                            </a>
                                            <span class="btn btn-outline-danger btn-sm square-btn delete-data"
                                                  title="{{ translate('delete') }}"
                                                  data-id="product-{{ $restockProduct->id}}">
                                                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/restock_delete.svg') }}" alt="">
                                            </span>
                                        </div>
                                        <form action="{{ route('vendor.products.restock-delete',[$restockProduct->id]) }}"
                                              method="post" id="product-{{ $restockProduct->id}}">
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

                    @if(count($restockProducts)==0)
                        @include('layouts.vendor.partials._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
    <span id="message-select-word" data-text="{{ translate('select') }}"></span>
    <div class="modal fade update-stock-modal restock-stock-update" id="update-stock" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('vendor.products.update-quantity') }}" method="post" class="row">
                    <div class="modal-body">
                        @csrf
                        <div class="rest-part-content"></div>
                        <div class="d-flex justify-content-end gap-10 flex-wrap align-items-center">
                            <button type="button" class="btn btn-danger px-4" data-dismiss="modal" aria-label="Close">
                                {{ translate('close') }}
                            </button>
                            <button class="btn btn--primary" class="btn btn--primary px-4" type="submit">
                                {{ translate('update') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('vendor-views.product.partials.offcanvas._restock-list-filter-offcanvas')
@endsection
@push('script')
    <script type="text/javascript">
        changeInputTypeForDateRangePicker($('input[name="restock_date"]'));

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
