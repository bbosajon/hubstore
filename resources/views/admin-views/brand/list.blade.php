@extends('layouts.admin.app')

@section('title', translate('brand_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/brand.png') }}" alt="">
                {{ translate('brand_Setup') }}
            </h2>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-20">
                        <div class="d-flex justify-content-between align-items-center gap-20 flex-wrap">
                            <h3 class="mb-0">
                                {{ translate('brand_list') }}
                                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">
                                    {{ $brands->total() }}
                                </span>
                            </h3>
                            <div class="d-flex flex-wrap gap-3 align-items-stretch">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group flex-grow-1 max-w-280">
                                        <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                            placeholder="{{ translate('search_by_brand_name') }}"
                                            aria-label="{{ translate('search_by_brand_name') }}"
                                            value="{{ request('searchValue') }}" >
                                        <div class="input-group-append search-submit">
                                            <button type="submit">
                                                <i class="fi fi-rr-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="dropdown">
                                    <a type="button" class="btn btn-outline-primary"
                                        href="{{ route('admin.brand.export', ['searchValue' => request('searchValue')]) }}">
                                        <i class="fi fi-sr-inbox-in"></i>
                                        <span class="fs-12">{{ translate('export') }}</span>
                                    </a>
                                </div>
                                <button class="btn btn-primary" title="{{ translate('Add') }}"
                                        data-bs-toggle="offcanvas" href="#brandAddOffcanvas"
                                > + {{ translate('Add_New') }}</button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-borderless align-middle">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th>{{ translate('brand_name') }}</th>
                                        <th>{{ translate('Image_alt_text') }}</th>
                                        <th class="text-center">{{ translate('total_Product') }}</th>
                                        <th class="text-center">{{ translate('total_Order') }}</th>
                                        <th class="text-center">{{ translate('status') }}</th>
                                        <th class="text-center">{{ translate('action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @if(count($brands) > 0)
                                    @foreach ($brands as $key => $brand)
                                        <tr>
                                            <td>{{ $brands->firstItem() + $key }}</td>
                                            <td>
                                                <div class="d-flex gap-2 align-items-center">
                                                    <div class="avatar-60 d-flex align-items-center rounded">
                                                        <img class="img-fluid h-100 object-fit-cover w-100 rounded" alt=""
                                                            src="{{ getStorageImages(path: $brand->image_full_url, type: 'backend-brand') }}">
                                                    </div>

                                                    <div class="overflow-hidden max-w-100px">
                                                        <div class="fs-14 line-1 max-w-200 text-truncate" data-bs-toggle="tooltip"
                                                            data-bs-placement="right" aria-label="{{ $brand['defaultname'] }}"
                                                            data-bs-title="{{ $brand['defaultname'] }}">{{ $brand['defaultname'] }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$brand['image_alt_text'] ?? '-'}}</td>
                                            <td class="text-center">{{ $brand['brand_all_products_count'] }}</td>
                                            <td class="text-center">
                                                {{ $brand['brandAllProducts']->sum('order_details_count') }}</td>
                                            <td>
                                                <form action="{{ route('admin.brand.status-update') }}" method="post"
                                                    id="brand-status{{ $brand['id'] }}-form" class="no-reload-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $brand['id'] }}">
                                                    <label class="switcher mx-auto" for="brand-status{{ $brand['id'] }}">
                                                        <input class="switcher_input custom-modal-plugin" type="checkbox"
                                                            value="1" name="status"
                                                            id="brand-status{{ $brand['id'] }}"
                                                            {{ $brand['status'] == 1 ? 'checked' : '' }}
                                                            data-modal-type="input-change-form" data-reload="true"
                                                            data-modal-form="#brand-status{{ $brand['id'] }}-form"
                                                            data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/brand-status-on.png') }}"
                                                            data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/brand-status-off.png') }}"
                                                            data-on-title = "{{ translate('Want_to_Turn_ON') . ' ' . $brand['defaultname'] . ' ' . translate('status') }}"
                                                            data-off-title = "{{ translate('Want_to_Turn_OFF') . ' ' . $brand['defaultname'] . ' ' . translate('status') }}"
                                                            data-on-message = "<p>{{ translate('if_enabled_this_brand_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                            data-off-message = "<p>{{ translate('if_disabled_this_brand_will_be_hidden_from_the_website_and_customer_app') }}</p>"
                                                            data-on-button-text="{{ translate('turn_on') }}"
                                                            data-off-button-text="{{ translate('turn_off') }}">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="d-flex justify-content-center gap-3">
                                                    <a class="btn btn-outline-success icon-btn" title="{{ translate('View') }}"
                                                    data-bs-toggle="offcanvas" href="#brandViewOffcanvas-{{ $brand['id'] }}">
                                                        <i class="fi fi-sr-eye"></i>
                                                    </a>
                                                    <a class="btn btn-outline-info icon-btn"
                                                        title="{{ translate('edit') }}"
                                                        data-bs-toggle="offcanvas" href="#brandEditOffcanvas-{{ $brand['id'] }}">
                                                        <i class="fi fi-sr-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-outline-danger icon-btn" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$brand['id']}}">
                                                        <i class="fi fi-rr-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="d-flex justify-content-lg-end">
                                {{ $brands->links() }}
                            </div>
                        </div>
                        @if (count($brands) == 0)
                            @include(
                                'layouts.admin.partials._empty-state',
                                ['text' => 'no_brand_found'],
                                ['image' => 'default']
                            )
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="route-admin-brand-delete" data-url="{{ route('admin.brand.delete') }}"></span>
    <span id="route-admin-brand-status-update" data-url="{{ route('admin.brand.status-update') }}"></span>
    <span id="get-brands" data-brands="{{ json_encode($brands) }}"></span>
    @include("admin-views.brand.partials._select-brand-delete-modal", ['brands' => $brands])
    @if(count($brands) > 0)
        @foreach ($brands as $key => $brand)
            @include("admin-views.brand.offcanvas._brand-view",['brand' => $brand])
            @include("admin-views.brand.offcanvas._brand-edit", ['brand' => $brand])
           @include("admin-views.brand.partials._delete-modal", ['brand' => $brand])
        @endforeach
    @endif
    @include("admin-views.brand.offcanvas._brand-add")

@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/backend/admin/js/products/products-management.js') }}"></script>
@endpush
