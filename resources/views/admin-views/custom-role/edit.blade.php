@extends('layouts.admin.app')

@section('title', translate('edit_Role'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2 text-capitalize">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/add-new-seller.png') }}" alt="">
                {{ translate('role_update') }}
            </h2>
        </div>
        <div class="card">
            <div class="card-body">
                <form id="submit-create-role" action="{{ route('admin.custom-role.update',[$role['id']]) }}"
                      method="post" class="text-start">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <input type="hidden" name="id" value="{{ $role['id'] }}">
                            <div class="form-group mb-4">
                                <label for="name" class="form-label">{{ translate('role_name') }}</label>
                                <input type="text" name="name" value="{{ $role['name'] }}" class="form-control"
                                       id="name"
                                       aria-describedby="emailHelp"
                                       placeholder="{{ translate('ex').':'.translate('store') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-4 flex-wrap mb-4">
                        <label for="module" class="form-label mb-0">{{ translate('module_permission').':'}}</label>
                        <div class="form-group d-flex gap-2">
                            <input type="checkbox" id="select-all-module"
                                   class="form-check-input checkbox--input checkbox--input_lg cursor-pointer">
                            <label class="form-check-label cursor-pointer text-capitalize"
                                   for="select-all-module">{{ translate('select_all') }}</label>
                        </div>
                    </div>

                    <div class="row gy-2 mb-4">
                        @foreach($employeeRolePermission as $employeeRoleKey => $employeeRole)
                            <div class="col-sm-6 col-lg-3">
                                <div class="form-group d-flex gap-2">
                                    <input type="checkbox" name="modules[]" value="{{ $employeeRoleKey }}"
                                           class="form-check-input checkbox--input module-permission"
                                           id="{{ $employeeRoleKey }}-permission"
                                        {{ in_array($employeeRoleKey, (array)json_decode($role['module_access'])) ? 'checked' : '' }}>
                                    <label class=""
                                           style="{{ session('direction') === "rtl" ? 'margin-right: 1.25rem;' : '' }};"
                                           for="{{ $employeeRoleKey }}-permission">
                                        {{ translate($employeeRole) }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <button type="reset" class="btn btn-secondary">{{ translate('reset') }}</button>
                        <button type="submit" class="btn btn-primary">{{ translate('update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/backend/admin/js/user-management/custom-role.js') }}"></script>
@endpush
