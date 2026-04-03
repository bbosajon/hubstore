@extends('layouts.admin.app')

@section('title', translate('withdraw_method_list'))
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <div class="page-title-wrap d-flex justify-content-between flex-wrap align-items-center gap-3 mb-3">
                <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/withdraw-icon.png')}}" alt="">
                    {{translate('withdraw_method_list')}}
                </h2>
                <a href="{{route('admin.vendors.withdraw-method.add')}}" class="btn btn-primary">+ {{translate('add_method')}}</a>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row gy-1 align-items-center justify-content-between mb-4">
                    <div class="col-auto">
                        <h3> {{ translate('methods')}}
                            <span class="badge badge-info text-bg-info"> {{ $withdrawalMethods->total() }}</span>
                        </h3>
                    </div>
                    <div class="col-auto">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                       placeholder="{{translate('search_Method_Name')}}" aria-label="Search orders"
                                       value="{{ request('searchValue') }}" >
                                <div class="input-group-append search-submit">
                                    <button type="submit">
                                        <i class="fi fi-rr-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table id="datatable" class="table table-hover table-borderless table-nowrap align-middle">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('method_name')}}</th>
                                <th>{{ translate('method_fields') }}</th>
                                <th class="text-center">{{translate('active_status')}}</th>
                                <th class="text-center">{{translate('default_method')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($withdrawalMethods as $key => $withdrawalMethod)
                            <tr>
                                <td>{{$withdrawalMethods->firstitem() + $key}}</td>
                                <td>{{$withdrawalMethod['method_name']}}</td>
                                <td>
                                    @foreach($withdrawalMethod['method_fields'] as $methodField)
                                        <span class="badge badge-success opacity-75 text-bg-success">
                                            <b>{{translate('name').':'}}</b> {{translate($methodField['input_name'])}} |
                                            <b>{{translate('type').':'}}</b> {{ $methodField['input_type'] }} |
                                            <b>{{translate('placeholder').':'}}</b> {{ $methodField['placeholder'] }} |
                                            <b>{{translate('is_Required').':'}}</b> {{ $methodField['is_required'] ? translate('yes') : translate('no') }}
                                        </span><br/>
                                    @endforeach
                                </td>
                                <td>
                                    <form action="{{route('admin.vendors.withdraw-method.status-update')}}"
                                          method="post" id="withdrawal-method-status{{$withdrawalMethod['id']}}-form"
                                          class="no-reload-form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$withdrawalMethod['id']}}">
                                        <label class="switcher mx-auto" for="withdrawal-method-status{{$withdrawalMethod['id']}}">
                                            <input
                                                class="switcher_input custom-modal-plugin"
                                                type="checkbox" value="1" name="status"
                                                id="withdrawal-method-status{{$withdrawalMethod['id']}}"
                                                {{ $withdrawalMethod['is_active'] == 1 ? 'checked':'' }}
                                                data-modal-type="input-change-form"
                                                data-modal-form="#withdrawal-method-status{{$withdrawalMethod['id']}}-form"
                                                data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/wallet-on.png') }}"
                                                data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/wallet-off.png') }}"
                                                data-on-title = "{{translate('want_to_Turn_ON_This_Withdraw_Method').'?'}}"
                                                data-off-title = "{{translate('want_to_Turn_OFF_This_Withdraw_Method').'?'}}"
                                                data-on-message = "<p>{{translate('if_you_enable_this_Withdraw_method_will_be_set_as_Default_Withdraw_Method_in_the_vendor_app_and_vendor_panel')}}</p>"
                                                data-off-message = "<p>{{translate('if_you_disable_this_Withdraw_method_will_be_remove_as_Default_Withdraw_Method_in_the_vendor_app_and_vendor_panel')}}</p>"
                                                data-on-button-text="{{ translate('turn_on') }}"
                                                data-off-button-text="{{ translate('turn_off') }}">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>

                                </td>
                                <td>
                                    <form action="{{route('admin.vendors.withdraw-method.default-status')}}" method="post"
                                          id="withdrawal-method-default{{$withdrawalMethod['id']}}-form"
                                          class="no-reload-form reload-true">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$withdrawalMethod['id']}}">
                                        <label class="switcher mx-auto" for="withdrawal-method-default{{$withdrawalMethod['id']}}">
                                            <input
                                                class="switcher_input custom-modal-plugin"
                                                type="checkbox" value="1" name="status"
                                                id="withdrawal-method-default{{$withdrawalMethod['id']}}"
                                                {{ $withdrawalMethod['is_default'] == 1 ? 'checked':'' }}
                                                data-modal-type="input-change-form"
                                                data-modal-form="#withdrawal-method-default{{$withdrawalMethod['id']}}-form"
                                                data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/wallet-on.png') }}"
                                                data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/wallet-off.png') }}"
                                                data-on-title = "{{translate('do_you_want_to_turn_on_this_as_the_default_withdraw_method').'?'}}"
                                                data-off-title = "{{translate('do_you_want_to_turn_off_this_as_the_default_withdraw_method').'?'}}"
                                                data-on-message = "<p>{{translate('if_you_enable_this_Withdraw_method_will_be_set_as_Default_Withdraw_Method_in_the_vendor_app_and_vendor_panel')}}</p>"
                                                data-off-message = "<p>{{translate('if_you_disable_this_Withdraw_method_will_be_remove_as_Default_Withdraw_Method_in_the_vendor_app_and_vendor_panel')}}</p>"
                                                data-on-button-text="{{ translate('turn_on') }}"
                                                data-off-button-text="{{ translate('turn_off') }}">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{route('admin.vendors.withdraw-method.edit',[$withdrawalMethod->id])}}" class="btn btn-outline-primary icon-btn">
                                            <i class="fi fi-rr-pencil"></i>
                                        </a>
                                        @if(!$withdrawalMethod->is_default)
                                            <a class="btn btn-outline-danger icon-btn delete-data" href="javascript:" title="{{translate('delete')}}" data-id="delete-{{$withdrawalMethod->id}}">
                                                <i class="fi fi-rr-trash"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <form action="{{route('admin.vendors.withdraw-method.delete',[$withdrawalMethod->id])}}" method="post" id="delete-{{$withdrawalMethod->id}}">
                                        @csrf @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-center justify-content-end">
                        {{$withdrawalMethods->links()}}
                    </div>
                </div>
                @if(count($withdrawalMethods)==0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_withdraw_method_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
    <span id="get-withdrawal-method-default-text"
          data-success="{{translate('default_method_updated_successfully').'.'}}"
          data-error="{{translate('default_Method_updated_failed').'!!'}}">
    </span>
@endsection
