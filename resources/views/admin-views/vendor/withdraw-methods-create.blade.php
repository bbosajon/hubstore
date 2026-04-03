@extends('layouts.admin.app')

@section('title', translate('Withdrawal_Methods'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="page-title text-capitalize h1">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{translate('withdrawal_methods')}}
                </h2>
                <button class="btn btn-primary" id="add-more-field">
                    <i class="fi fi-rr-plus-small"></i> {{translate('add_Fields')}}
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <form action="{{route('admin.vendors.withdraw-method.add')}}" method="POST" novalidate="novalidate">
                    @csrf
                    <div class="card card-body">
                        <div class="form-floating">
                            <input type="text" class="form-control" name="method_name" id="method_name"
                                   placeholder="{{translate('select_method_name')}}" value=""  data-required-msg="{{translate('method_name_field_is_required')}}" required>
                            <label>{{translate('method_name').' '.'*'}}</label>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div id="custom-field-section">
                            <div class="card card-body">
                                <div class="row gy-4 align-items-center">
                                    <div class="col-md-6">
                                        <label class="mb-2">{{translate('input_Field_Type').' '.'*'}}</label>
                                        <select class="custom-select" name="field_type[]"  data-required-msg="{{translate('input_field_type_is_required')}}" required>
                                            <option selected disabled>{{'--'. translate('select_Field_Type') . '--'}}</option>
                                            <option value="string">{{translate('string')}}</option>
                                            <option value="number">{{translate('number')}}</option>
                                            <option value="date">{{translate('date')}}</option>
                                            <option value="password">{{translate('password')}}</option>
                                            <option value="email">{{translate('email')}}</option>
                                            <option value="phone">{{translate('phone')}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="">
                                            <label class="mb-2">{{translate('field_name').' '.'*'}}</label>
                                            <input type="text" class="form-control" name="field_name[]"
                                                   data-required-msg="{{translate('field_name_is_required')}}"
                                                   placeholder="{{translate('select_field_name')}}" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="">
                                            <label class="mb-2">{{translate('placeholder_text').' '.'*'}}</label>
                                            <input type="text" class="form-control" name="placeholder_text[]"
                                                   data-required-msg="{{translate('placeholder_field_is_required')}}"
                                                   placeholder="{{translate('select_placeholder_text')}}" value="" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-1">
                                            <input class="form-check-input checkbox--input" type="checkbox" value="1" name="is_required[0]" id="flex-check-default--0" checked>
                                            <label class="form-check-label" for="flex-check-default--0">
                                                {{translate('this_field_required')}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex my-3">
                            <div class="d-flex align-items-center gap-1">
                                <input class="form-check-input checkbox--input" type="checkbox" value="1" name="is_default" id="flex-check-default-method">
                                <label class="form-check-label" for="flex-check-default-method">
                                    {{translate('default_method')}}
                                </label>
                            </div>
                        </div>
                        <div class="d-flex gap-3 justify-content-end mt-4">
                            <button type="reset" class="btn btn-secondary">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn-primary demo_check">{{translate('submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span id="get-add-filed-text"
          data-input-filed="{{translate('input_field_type')}}"
          data-string="{{translate('string')}}"
          data-number="{{translate('number')}}"
          data-date="{{translate('date')}}"
          data-password="{{translate('password')}}"
          data-email="{{translate('email')}}"
          data-phone="{{translate('phone')}}"
          data-field-name="{{translate('field_name')}}"
          data-placeholder-text="{{translate('placeholder_text')}}"
          data-required="{{translate('this_field_required')}}"
          data-remove="{{translate('remove')}}"
          data-reached-maximum="{{translate('reached_maximum')}}"
          data-confirm="{{translate('ok')}}"
    >
    </span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/new/back-end/js/admin/withdraw-method.js')}}"></script>
@endpush
