@php use Illuminate\Support\Str; @endphp
@extends('layouts.admin.app')

@section('title', translate('Customer_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/customer.png')}}" alt="">
                {{ translate('Customer_List') }}
                <span class="badge badge-soft-dark radius-50">{{ $totalCustomers }}</span>
            </h2>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="row align-items-end g-4">
                        <div class="col-md-4">
                            <label class="form-label">{{ translate('Order_Date') }}</label>
                            <div class="position-relative">
                                <span class="fi fi-sr-calendar icon-absolute-on-right"></span>
                                <input type="text" name="order_date" class="js-daterangepicker-with-range form-control cursor-pointer" value="{{request('order_date')}}" placeholder="{{ translate('Select_Date') }}" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{translate('Customer_Joining_Date')}}</label>
                            <div class="position-relative">
                                <span class="fi fi-sr-calendar icon-absolute-on-right cursor-pointer"></span>
                                <input type="text" name="customer_joining_date" class="js-daterangepicker-with-range form-control cursor-pointer" value="{{request('customer_joining_date')}}" placeholder="{{ translate('Select_Date') }}" autocomplete="off" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{translate('Customer_Status')}}</label>
                            <div class="select-wrapper">
                                <select class="form-select set-filter" name="is_active">
                                    <option {{ !request()->has('is_active') ?'selected':''}} disabled>{{ translate('select_status') }}</option>
                                    <option {{ request()->has('is_active') && request('is_active') == '' ?'selected':''}} value="">{{ translate('All') }}</option>
                                    <option {{ request('is_active')  == '1'?'selected':''}} value="1">{{ translate('Active') }}</option>
                                    <option {{ request('is_active')  == '0'?'selected':''}} value="0">{{ translate('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{translate('Sort_By') }}</label>
                            <div class="select-wrapper">
                                <select class="form-select" name="sort_by">
                                    <option disabled {{ is_null(request('sort_by')) ? 'selected' : '' }}>{{ translate('Select_Customer_sorting_order') }}</option>
                                    <option value="order_amount">{{ translate('Sort_By_Order_Amount') }}</option>
                                    <option value="asc" {{ request('sort_by') === 'asc' ? 'selected' : '' }}>{{translate('Sort_By_Oldest')}}</option>
                                    <option value="desc" {{ request('sort_by') === 'desc' ? 'selected' : '' }}>{{translate('Sort_By_Newest')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{translate('Choose_First')}}</label>
                            <input type="number" class="form-control"  min="1" value="{{ request('choose_first') }}" placeholder="{{translate('Ex')}} : {{translate('100')}}" name="choose_first">
                        </div>
                        <div class="col-md-4">
                            <label class="d-md-block">&nbsp;</label>
                            <div class="d-flex gap-3 justify-content-end">
                                <a href="{{ route('admin.customer.list') }}"
                                   class="btn btn-secondary btn-block">
                                    {{ translate('reset') }}
                                </a>
                                <button type="submit" class="btn btn-primary btn-block">{{translate('Filter')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-4">
                    <h3 class="mb-0">
                        {{translate('Customer_List')}}
                        <span class="badge badge-info text-bg-info">{{ $customers->total() }}</span>
                    </h3>

                    <div class="d-flex gap-3 align-items-center flex-wrap">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="hidden" name="order_date" value="{{request('order_date')}}">
                                    <input type="hidden" name="customer_joining_date" value="{{request('customer_joining_date')}}">
                                    <input type="hidden" name="is_active" value="{{request('is_active')}}">
                                    <input type="hidden" name="sort_by" value="{{request('sort_by')}}">
                                    <input type="hidden" name="choose_first" value="{{request('choose_first')}}">
                                    <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                           placeholder="{{ translate('search_by_Name_or_Email_or_Phone')}}"  aria-label="Search orders" value="{{ request('searchValue') }}">
                                    <div class="input-group-append search-submit">
                                        <button type="submit">
                                            <i class="fi fi-rr-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <a type="button" class="btn btn-outline-primary" href="{{route('admin.customer.export', ['sort_by' => request('sort_by'), 'choose_first' => request('choose_first'),'is_active' => request('is_active'), 'order_date' => request('order_date'),'customer_joining_date' => request('customer_joining_date'),  'searchValue' => request('searchValue')])}}">
                            <i class="fi fi-sr-inbox-in"></i>
                            <span class="fs-12">{{ translate('export') }}</span>
                        </a>
                    </div>
                </div>
                <div class="table-responsive datatable-custom">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('customer_name')}}</th>
                                <th>{{translate('contact_info')}}</th>
                                <th>{{translate('total_Order')}} </th>
                                <th class="text-center">{{translate('block')}} / {{translate('unblock')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $key=>$customer)
                            <tr>
                                <td>
                                    {{$customers->firstItem()+$key}}
                                </td>
                                <td>
                                    <a href="{{route('admin.customer.view',[$customer['id']])}}"
                                    class="text-dark text-hover-primary d-flex align-items-center gap-10">
                                        <img src="{{getStorageImages(path:$customer->image_full_url,type:'backend-profile')}}"
                                            class="avatar rounded-circle object-fit-cover" alt="" width="40">
                                        {{Str::limit($customer['f_name']." ".$customer['l_name'],20)}}
                                    </a>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <strong><a class="text-dark text-hover-primary"
                                                href="mailto:{{$customer->email}}">{{$customer->email}}</a></strong>

                                    </div>
                                    <a class="text-dark text-hover-primary" href="tel:{{$customer->phone}}">{{$customer->phone}}</a>

                                </td>
                                <td>
                                    <label class="badge badge-info text-bg-info">
                                        {{$customer?->orders?->count() ?? 0}}
                                    </label>
                                </td>
                                <td>
                                    @if($customer['email'] == 'walking@customer.com')
                                        <div class="text-center">
                                            <div class="badge badge-soft-version">{{ translate('default') }}</div>
                                        </div>
                                    @else
                                        <form action="{{route('admin.customer.status-update')}}" method="post"
                                            id="customer-status{{$customer['id']}}-form" class="no-reload-form">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$customer['id']}}">
                                            <label class="switcher mx-auto" for="customer-status{{$customer['id']}}">
                                                <input
                                                    class="switcher_input custom-modal-plugin"
                                                    type="checkbox" value="1" name="is_active"
                                                    id="customer-status{{$customer['id']}}"
                                                    {{ $customer['is_active'] == 1 ? 'checked':'' }}
                                                    data-modal-type="input-change-form"
                                                    data-modal-form="#customer-status{{$customer['id']}}-form"
                                                    data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/customer-block-on.png') }}"
                                                    data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/customer-block-off.png') }}"
                                                    data-on-title = "{{translate('want_to_unblock').' '.$customer['f_name'].' '.$customer['l_name'].'?'}}"
                                                    data-off-title = "{{translate('want_to_block').' '.$customer['f_name'].' '.$customer['l_name'].'?'}}"
                                                    data-on-message = "<p>{{translate('if_enabled_this_customer_will_be_unblocked_and_can_log_in_to_this_system_again')}}</p>"
                                                    data-off-message = "<p>{{translate('if_disabled_this_customer_will_be_blocked_and_cannot_log_in_to_this_system')}}</p>"
                                                    data-on-button-text="{{ translate('turn_on') }}"
                                                    data-off-button-text="{{ translate('turn_off') }}">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    @endif
                                </td>

                                <td>
                                    <div class="d-flex justify-content-center gap-2">
                                        <a title="{{translate('view')}}"
                                        class="btn btn-outline-info icon-btn"
                                        href="{{route('admin.customer.view',[$customer['id']])}}">
                                        <i class="fi fi-rr-eye"></i>
                                        </a>
                                        @if($customer['id'] != '0')
                                            <a title="{{translate('delete')}}"
                                            class="btn btn-outline-danger delete icon-btn delete-data" href="javascript:"
                                            data-id="customer-{{$customer['id']}}">
                                            <i class="fi fi-rr-trash"></i>
                                            </a>
                                        @endif
                                    </div>
                                    <form action="{{route('admin.customer.delete',[$customer['id']])}}"
                                        method="post" id="customer-{{$customer['id']}}">
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
                        {!! $customers->links() !!}
                    </div>
                </div>
                @if(count($customers)==0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_customer_found'],['image'=>'default'])
                @endif
            </div>
    </div>
    </div>
@endsection

@push('script')
    <script type="text/javascript">
        changeInputTypeForDateRangePicker($('input[name="order_date"]'));
        changeInputTypeForDateRangePicker($('input[name="customer_joining_date"]'));
    </script>
@endpush
