@extends('layouts.admin.app')

@section('title', translate('employee_list'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/employee.png')}}" width="20" alt="">
                {{translate('employee_list')}}
            </h2>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-3 flex-wrap align-items-center mb-4">
                    <h3 class="mb-0 text-capitalize gap-2">
                        {{translate('employee_table')}}
                        <span class="badge badge-info text-bg-info">{{$employees->total()}}</span>
                    </h3>

                    <div class="align-items-center d-flex gap-3 flex-wrap">
                        <div class="">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group">
                                    <input type="search" name="searchValue" class="form-control"
                                           placeholder="{{translate('search_by_name_or_email_or_phone')}}"
                                           value="{{ request('searchValue') }}" >
                                    <div class="input-group-append search-submit">
                                        <button type="submit">
                                            <i class="fi fi-rr-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="d-flex gap-2 align-items-center text-left">
                                    <div class="select-wrapper">
                                        <select class="form-select text-ellipsis min-w-200" name="admin_role_id">
                                            <option value="all" {{ request('employee_role') == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                            @foreach($employee_roles as $employee_role)
                                                <option value="{{ $employee_role['id'] }}" {{ request('admin_role_id') == $employee_role['id'] ? 'selected' : '' }}>
                                                        {{ ucfirst($employee_role['name']) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="">
                                        <button type="submit" class="btn btn-primary text-nowrap">
                                            {{ translate('filter')}}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <a type="button" class="btn btn-outline-primary" href="{{route('admin.employee.export',['role'=>request('admin_role_id'),'searchValue'=>request('searchValue')])}}">
                            <i class="fi fi-sr-inbox-in"></i>
                            <span class="fs-12">{{ translate('export') }}</span>
                        </a>

                        <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary text-nowrap">
                            <i class="fi fi-rr-plus-small"></i>
                            <span class="text ">{{translate('add_new')}}</span>
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-hover table-borderless align-middle card-table w-100">
                        <thead class="thead-light text-capitalize table-nowrap">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>{{translate('name')}}</th>
                            <th>{{translate('email')}}</th>
                            <th>{{translate('phone')}}</th>
                            <th>{{translate('role')}}</th>
                            <th>{{translate('status')}}</th>
                            <th class="text-center">{{translate('action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($employees as $key => $employee)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td class="text-capitalize">
                                    <div class="media align-items-center gap-10">
                                        <img class="rounded-circle ratio-1 object-fit-cover" width="50" alt="" src="{{getStorageImages(path: $employee->image_full_url,type:'backend-profile')}}">
                                        <div class="media-body">
                                            {{$employee['name']}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(!empty($employee['email']))
                                        <a href="mailto:{{ $employee['email'] }}" class="text-decoration-none text-dark">{{ $employee['email'] }}</a>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($employee['phone']))
                                        <a href="tel:{{ $employee['phone'] }}" class="text-decoration-none text-dark">{{ $employee['phone'] }}</a>
                                    @endif
                                </td>
                                <td>
                                    {{ $employee?->role?->name ? ucwords($employee?->role?->name) : translate('role_not_found') }}
                                </td>
                                <td>
                                    @if($employee['id'] == 1)
                                        <label class="badge badge-primary-light">{{ translate('admin') }}</label>
                                    @else
                                        <form action="{{route('admin.employee.status')}}" method="post" id="employee-id-{{$employee['id']}}-form" class="no-reload-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$employee['id']}}">
                                            <label class="switcher mx-auto" for="employee-id-{{$employee['id']}}">
                                                <input
                                                    class="switcher_input custom-modal-plugin"
                                                    type="checkbox" value="1" name="status"
                                                    id="employee-id-{{$employee['id']}}"
                                                    {{$employee->status?'checked':''}}
                                                    data-modal-type="input-change-form"
                                                    data-modal-form="#employee-id-{{$employee['id']}}-form"
                                                    data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/employee-on.png') }}"
                                                    data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/employee-off.png') }}"
                                                    data-on-title = "{{translate('want_to_Turn_ON_Employee_Status').'?'}}"
                                                    data-off-title = "{{translate('want_to_Turn_OFF_Employee_Status').'?'}}"
                                                    data-on-message = "<p>{{translate('if_enabled_this_employee_can_log_in_to_the_system_and_perform_his_role')}}</p>"
                                                    data-off-message = "<p>{{translate('if_disabled_this_employee_can_not_log_in_to_the_system_and_perform_his_role')}}</p>"
                                                    data-on-button-text="{{ translate('turn_on') }}"
                                                    data-off-button-text="{{ translate('turn_off') }}">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($employee['id'] == 1)
                                        <label class="badge badge-primary-light">{{ translate('default') }}</label>
                                    @else
                                        <div class="d-flex gap-10 justify-content-center">
                                            <a href="{{route('admin.employee.update',[$employee['id']])}}"
                                               class="btn btn-outline-primary icon-btn"
                                               title="{{translate('edit')}}">
                                               <i class="fi fi-rr-pencil"></i>
                                            </a>
                                            <a class="btn btn-outline-info icon-btn" title="View" href="{{route('admin.employee.view',['id'=>$employee['id']])}}">
                                                <i class="fi fi-rr-eye"></i>
                                            </a>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-end">
                        {{$employees->links()}}
                    </div>
                </div>
                @if(count($employees)==0)
                    <div class="w-100">
                        @include('layouts.admin.partials._empty-state',['text'=>'no_employee_found'],['image'=>'default'])
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
