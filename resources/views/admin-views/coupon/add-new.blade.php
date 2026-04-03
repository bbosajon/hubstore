@extends('layouts.admin.app')

@section('title', translate('coupon_Add'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/coupon_setup.png') }}" alt="">
                {{translate('coupon_setup')}}
            </h2>
        </div>

        <div class="row">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.coupon.add')}}" method="POST" class="" novalidate="novalidate" id="coupon-add-ajax-submit">
                            @csrf
                            <div class="row g-4 mb-4">
                                <div class="col-md-6 col-lg-4">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('coupon_type')}} <span class="text-danger">*</span></label>
                                    <div class="select-wrapper">
                                        <select class="form-select" id="coupon_type" name="coupon_type" data-required-msg="{{ translate('coupon_type_is_required') }}" required>
                                            <option disabled selected>{{translate('select_coupon_type')}}</option>
                                            <option
                                                value="discount_on_purchase">{{translate('discount_on_Purchase')}}</option>
                                            <option value="free_delivery">{{translate('free_Delivery')}}</option>
                                            <option value="first_order">{{translate('first_Order')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="name"
                                               class="form-label d-flex">{{translate('coupon_title')}} <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" value="{{ old('title') }}"
                                               id="title"  data-maxlength="80"
                                               placeholder="{{translate('title')}}" required  data-required-msg="{{ translate('coupon_title_is_required') }}">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="d-flex justify-content-between">
                                        <label for="name"
                                               class="form-label text-capitalize">{{translate('coupon_code')}} <span class="text-danger">*</span></label>
                                        <a href="javascript:" class="text-primary fs-12" id="generateCode">{{translate('generate_code')}}</a>
                                    </div>
                                    <input type="text" name="code" value=""
                                           class="form-control" id="code"
                                           placeholder="{{translate('ex')}}: EID100" required  data-required-msg="{{ translate('coupon_code_is_required') }}">
                                </div>
                                <div class="col-md-6 col-lg-4 first_order">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('coupon_bearer')}} <span class="text-danger">*</span></label>
                                    <div class="select-wrapper">
                                        <select class="form-select" name="coupon_bearer" id="coupon_bearer" required  data-required-msg="{{ translate('coupon_bearer_is_required') }}">
                                            <option disabled selected>{{translate('select_coupon_bearer')}}</option>
                                            <option value="seller">{{translate('vendor')}}</option>
                                            <option value="inhouse">{{translate('admin')}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 coupon_by first_order">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('vendor')}} <span class="text-danger">*</span></label>
                                    <select
                                        class="custom-select"
                                        name="seller_id" id="vendor_wise_coupon"  data-required-msg="{{ translate('vendor_field_is_required') }}">
                                        <option disabled selected>{{translate('select_vendor')}}</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-lg-4 coupon_type first_order">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('customer')}} <span class="text-danger">*</span></label>
                                    <select
                                        class="custom-select"
                                        name="customer_id">
                                        <option disabled selected>{{translate('select_customer')}}</option>
                                        <option value="0">{{translate('All_Customer')}}</option>
                                        @foreach($customers as $customer)
                                            <option
                                                value="{{ $customer->id }}">{{ $customer->f_name. ' '. $customer->l_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 col-lg-4 first_order">
                                    <label
                                        for="exampleFormControlInput1"
                                        class="form-label d-flex">{{translate('limit_for_same_user')}} <span class="text-danger">*</span></label>
                                    <input type="number" name="limit" value="{{ old('limit') }}" min="0"
                                           id="coupon_limit" class="form-control"
                                           placeholder="{{translate('ex')}}: 10">
                                </div>
                                <div class="col-md-6 col-lg-4 free_delivery">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('discount_type')}}<span class="text-danger">*</span></label>
                                    <div class="select-wrapper">
                                        <select id="discount_type" class="form-select" name="discount_type">
                                            <option value="amount">{{translate('amount')}}</option>
                                            <option value="percentage">{{translate('percentage')}} (%)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4 free_delivery">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('discount_Amount')}}<span class="text-danger">*</span>
                                        <span id="discount_currency">({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})</span>
                                        <span id="discount_percent"> (%)</span></label>
                                    <input type="number" min="1" max="1000000" name="discount"
                                           value="{{ old('discount') }}" class="form-control"
                                           id="discount"
                                           placeholder="{{translate('ex')}} : 500">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="name"
                                           class="form-label d-flex">
                                        {{ translate('minimum_purchase') }}
                                        <span class="text-danger">*</span>
                                        ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})
                                    </label>
                                    <input type="number" min="1" max="1000000" name="min_purchase"  data-required-msg="{{ translate('Minimum_purchase_field_is_required') }}"
                                           value="{{ old('min_purchase') }}" class="form-control"
                                           id="minimum purchase"
                                           placeholder="{{translate('ex')}} : 100" required>
                                </div>
                                <div class="col-md-6 col-lg-4 free_delivery" id="max-discount">
                                    <label for="name"
                                           class="form-label d-flex">
                                        {{translate('maximum_discount')}}
                                        ({{ getCurrencySymbol(currencyCode: getCurrencyCode()) }})
                                    </label>
                                    <input type="number" min="1" max="1000000" name="max_discount"
                                           value="{{ old('max_discount') }}"
                                           class="form-control" id="maximum discount"
                                           placeholder="{{translate('ex')}} : 5000">
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('Start_Date')}} <span class="text-danger">*</span></label>
                                    <input id="start_date" type="date" name="start_date" value="{{ old('start_date') }}"
                                           class="form-control"
                                           placeholder="{{translate('Start_Date')}}" required>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <label for="name"
                                           class="form-label d-flex">{{translate('expire_date')}} <span class="text-danger">*</span></label>
                                    <input id="expire_date" type="date" name="expire_date"
                                           value="{{ old('expire_date') }}" class="form-control"
                                           placeholder="{{translate('expire_date')}}" required>
                                </div>
                            </div>

                            <div class="d-flex align-items-center justify-content-end flex-wrap gap-3">
                                <button type="reset" class="btn btn-secondary px-4">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn-primary px-4">{{translate('submit')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-20">
                        <div class="d-flex justify-content-between align-items-center gap-20 flex-wrap">
                            <h3 class="mb-0">
                                {{translate('coupon_list')}}
                                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">{{ $coupons->total() }}</span>
                            </h3>
                            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-sm-end flex-grow-1">
                                <div class="flex-grow-1 max-w-300 min-w-100-mobile">
                                    <form action="{{ url()->current() }}" method="GET">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                           placeholder="{{translate('search_by_Title_or_Code_or_Discount_Type')}}"
                                           value="{{ request('searchValue') }}" aria-label="Search orders" required>
                                            <div class="input-group-append search-submit">
                                                <button type="submit">
                                                    <i class="fi fi-rr-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="dropdown">
                                    <a type="button" class="btn btn-outline-primary min-h-40" href="{{ route('admin.coupon.export',['searchValue'=>request('searchValue')]) }}">
                                        <i class="fi fi-sr-inbox-in"></i>
                                        <span class="fs-12">{{ translate('export') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable"
                                   class="table table-hover table-borderless table-thead-bordered align-middle">
                                <thead class="text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('coupon')}}</th>
                                    <th>{{translate('coupon_type')}}</th>
                                    <th>{{translate('duration')}}</th>
                                    <th>{{translate('Limit')}}</th>
                                    <th class="text-center">{{translate('discount_bearer')}}</th>
                                    <th>{{translate('status')}}</th>
                                    <th class="text-center">{{translate('action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($coupons as $key => $coupon )
                                    <tr>
                                        <td>{{$coupons->firstItem() + $key }}</td>
                                        <td>
                                            <div class="max-w-250 text-truncate">{{$coupon['title']}}</div>
                                            <strong>{{translate('code')}}: {{$coupon['code'] }}</strong>
                                        </td>
                                        <td class="text-capitalize">{{translate(str_replace('_',' ',$coupon['coupon_type']))}}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-1">
                                                <span>{{date('d M, y',strtotime($coupon['start_date']))}} - </span>
                                                <span>{{date('d M, y',strtotime($coupon['expire_date']))}}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-2">
                                                <span>
                                                    {{translate('Same_User_Limit')}}:
                                                    <strong>{{ $coupon['limit'] }}</strong>
                                                </span>

                                                <span class="ml-1">
                                                    {{translate('Total_Used')}}:
                                                    <strong>{{ $coupon['order_count'] }}</strong>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ translate($coupon['coupon_bearer'] == 'inhouse' ? 'admin':$coupon['coupon_bearer']) }}</td>
                                        <td>
                                            <form
                                                action="{{route('admin.coupon.status',[$coupon['id'],$coupon['status']?0:1])}}"
                                                method="GET" id="coupon_status{{$coupon['id'] }}-form"
                                                class="coupon_status_form">
                                                <label class="switcher mx-auto" for="coupon_status{{$coupon['id'] }}">
                                                    <input
                                                        class="switcher_input custom-modal-plugin"
                                                        type="checkbox" value="1" name="status"
                                                        id="coupon_status{{$coupon['id'] }}"
                                                        {{ $coupon['status'] == 1 ? 'checked':'' }}
                                                        data-modal-type="input-change-form"
                                                        data-modal-form="#coupon_status{{$coupon['id'] }}-form"
                                                        data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/coupon-status-on.png') }}"
                                                        data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/coupon-status-off.png') }}"
                                                        data-on-title="{{translate('Want_to_Turn_ON_Coupon_Status').'?' }}"
                                                        data-off-title="{{translate('Want_to_Turn_OFF_Coupon_Status').'?' }}"
                                                        data-on-message="<p>{{translate('if_enabled_this_coupon_will_be_available_on_the_website_and_customer_app')}}</p>"
                                                        data-off-message="<p>{{translate('if_disabled_this_coupon_will_be_hidden_from_the_website_and_customer_app')}}</p>"
                                                        data-on-button-text="{{ translate('turn_on') }}"
                                                        data-off-button-text="{{ translate('turn_off') }}">
                                                    <span class="switcher_control"></span>
                                                </label>
                                            </form>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-3 justify-content-center">
                                                <button class="btn btn-outline-info btn-outline-info-dark icon-btn get-quick-view" data-id="{{ $coupon['id'] }}">
                                                    <i class="fi fi-sr-eye"></i>
                                                </button>
                                                <a class="btn btn-outline-info icon-btn edit"
                                                   href="{{route('admin.coupon.update',[$coupon['id']])}}"
                                                   title="{{ translate('edit')}}"
                                                >
                                                    <i class="fi fi-sr-pencil"></i>
                                                </a>
                                                <a class="btn btn-outline-danger icon-btn delete delete-data"
                                                   href="javascript:"
                                                   data-id="coupon-{{$coupon['id'] }}"
                                                   title="{{translate('delete')}}"
                                                >
                                                    <i class="fi fi-rr-trash"></i>
                                                </a>
                                                <form action="{{route('admin.coupon.delete',[$coupon['id']])}}"
                                                      method="post" id="coupon-{{$coupon['id'] }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div class="modal fade" id="quick-view" tabindex="-1" role="dialog"
                                 aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered coupon-details" role="document">
                                    <div class="modal-content border-0" id="quick-view-modal">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div class="px-4 d-flex justify-content-lg-end">
                                {{$coupons->links()}}
                            </div>
                        </div>

                        @if(count($coupons)==0)
                            @include('layouts.admin.partials._empty-state',['text'=>'no_coupon_found'],['image'=>'default'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <span id="coupon-bearer-url" data-url="{{route('admin.coupon.ajax-get-vendor')}}"></span>
    <span id="get-detail-url" data-url="{{ route('admin.coupon.quick-view-details') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/coupon.js')}}"></script>
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

            // --- free delivery collapse
            function toggleCollapse(wrapper) {
                let isChecked = wrapper.find('.free-del-input').is(':checked');

                if (isChecked) {
                    wrapper.find('.discount-type-div').slideUp(200);
                } else {
                    wrapper.find('.discount-type-div').slideDown(200);
                }
            }

            $(document).on('change', '.free-del-input', function () {
                let wrapper = $(this).closest('.free-delivery-collapse');
                toggleCollapse(wrapper);
            });

            $('.free-delivery-collapse').each(function () {
                toggleCollapse($(this));
            });

        });
    </script>
@endpush
