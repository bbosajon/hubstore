@extends('layouts.admin.app')

@section('title',translate('deliveryman_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img src="{{dynamicAsset(path: 'public/assets/back-end/img/deliveryman.png')}}" width="20" alt="">
                {{translate('delivery_man')}} <span class="badge badge-soft-dark radius-50 fs-12">{{ $deliveryMens->total() }}</span>
            </h2>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between gap-10 flex-wrap align-items-center mb-4">
                    <div class="">
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                       placeholder="{{translate('search_by_name').','.translate('_contact_info')}}" aria-label="Search" value="{{ request('searchValue') }}" >
                                <div class="input-group-append search-submit">
                                    <button type="submit">
                                        <i class="fi fi-rr-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="d-flex justify-content-end gap-3">
                        <a type="button" class="btn btn-outline-primary" href="{{ route('admin.delivery-man.export', ['searchValue' => request('searchValue'), 'sort_by' => request('sort_by')]) }}">
                            <i class="fi fi-sr-inbox-in"></i>
                            <span class="fs-12">{{ translate('export') }}</span>
                        </a>

                        <div class="d-flex flex-wrap gap-3 align-items-center justify-content-sm-end flex-grow-1">
                            <div class="dropdown">
                                <div class="position-relative">
                                    @if(!empty(request('sort_by')))
                                        <div class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2" style="--size: 14px;"></div>
                                    @endif
                                </div>
                                <button type="button" class="btn {{ !empty(request('sort_by')) ? 'btn-primary' : 'btn-outline-primary' }}" data-bs-toggle="dropdown">
                                    <i class="fi fi-sr-sort-alt"></i>
                                    <span class="fs-12">{{ translate('Sorting') }}</span>
                                    <i class="fi fi-rr-angle-small-down"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.delivery-man.list', ['sort_by' => 'latest', 'searchValue' => request('searchValue')]) }}">
                                            @if(empty(request('sort_by')) || request('sort_by') == 'latest')
                                                <span class="text-success pt-1">
                                                <i class="fi fi-sr-check-circle"></i>
                                            </span>
                                            @elseif(!empty(request('sort_by')) && request('sort_by') != 'latest')
                                                <span class="text-dark pt-1">
                                                <i class="fi fi-sr-circle"></i>
                                            </span>
                                            @endif
                                            {{ translate('Default') }} ({{ translate('Recent created') }})
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.delivery-man.list', ['sort_by' => 'oldest', 'searchValue' => request('searchValue')]) }}">
                                            @if(!empty(request('sort_by')) && request('sort_by') == 'oldest')
                                                <span class="text-success pt-1">
                                                <i class="fi fi-sr-check-circle"></i>
                                            </span>
                                            @else
                                                <span class="text-dark pt-1">
                                                <i class="fi fi-sr-circle"></i>
                                            </span>
                                            @endif
                                            {{ translate('Show Older First') }}
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center gap-2"
                                           href="{{ route('admin.delivery-man.list',['sort_by' => 'rating', 'searchValue' => request('searchValue')]) }}">
                                            @if(!empty(request('sort_by')) && request('sort_by') == 'rating')
                                                <span class="text-success pt-1">
                                                <i class="fi fi-sr-check-circle"></i>
                                            </span>
                                            @else
                                                <span class="text-dark pt-1">
                                                <i class="fi fi-sr-circle"></i>
                                            </span>
                                            @endif
                                            {{ translate('Top Delivery Man') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <a href="{{route('admin.delivery-man.add')}}" class="btn btn-primary text-nowrap">
                            <i class="fi fi-rr-plus-small"></i>
                            {{ translate('Add_Delivery_Man') }}
                        </a>
                    </div>
                </div>

                <div class="table-responsive datatable-custom">
                    <table class="table table-hover table-borderless table-thead-bordered align-middle card-table">
                        <thead class="thead-light thead-50 text-capitalize table-nowrap">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>{{translate('name')}}</th>
                                <th>{{translate('contact info')}}</th>
                                <th>{{translate('total_Orders')}}</th>
                                <th>{{translate('rating')}}</th>
                                <th class="text-center">{{translate('status')}}</th>
                                <th class="text-center">{{translate('action')}}</th>
                            </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($deliveryMens as $key => $deliveryMen)
                            <tr>
                                <td>{{$deliveryMens->firstitem()+$key}}</td>
                                <td>
                                    <div class="media align-items-center gap-10">
                                        <img class="rounded-circle aspect-1" width="50" alt="" src="{{getStorageImages(path:$deliveryMen->image_full_url,type:'backend-profile')}}">
                                        <div class="media-body overflow-hidden text-truncate">
                                            <a title="{{ $deliveryMen['f_name'].' '.$deliveryMen['l_name'] }}" class="text-dark text-hover-primary d-block text-wrap max-w-200" href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $deliveryMen['id']]) }}">
                                                {{ $deliveryMen['f_name'].' '.$deliveryMen['l_name'] }}
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <div><a class="text-dark text-hover-primary" href="mailto:{{$deliveryMen['email']}}"><strong>{{$deliveryMen['email']}}</strong></a></div>
                                        <a class="text-dark text-hover-primary" href="tel:{{$deliveryMen['country_code']}}{{$deliveryMen['phone']}}">
                                            {{ $deliveryMen['country_code'].$deliveryMen['phone'] }}</a>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.list', ['all', 'delivery_man_id' => $deliveryMen['id']]) }}" class="badge badge-info text-bg-info">
                                        <span>{{ $deliveryMen->orders_count }}</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.delivery-man.rating', ['id' => $deliveryMen['id']]) }}" class="badge badge-info text-bg-info">
                                        <span class="d-inline-flex align-items-center gap-1">{{ isset($deliveryMen->rating[0]->average) ? number_format($deliveryMen->rating[0]->average, 2, '.', ' ') : 0 }}
                                            <i class="fi fi-sr-star"></i>
                                        </span>
                                    </a>
                                </td>
                                <td>
                                    <form action="{{route('admin.delivery-man.status-update')}}"
                                          method="post" id="deliveryman_status{{$deliveryMen['id']}}-form"
                                          class="no-reload-form">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$deliveryMen['id']}}">
                                        <label class="switcher mx-auto" for="deliveryman_status{{$deliveryMen['id']}}">
                                            <input
                                                class="switcher_input custom-modal-plugin"
                                                type="checkbox" value="1" name="status"
                                                id="deliveryman_status{{$deliveryMen['id']}}"
                                                {{ $deliveryMen->is_active == 1 ? 'checked':'' }}
                                                data-modal-type="input-change-form"
                                                data-modal-form="#deliveryman_status{{$deliveryMen['id']}}-form"
                                                data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/deliveryman-status-on.png') }}"
                                                data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/deliveryman-status-off.png') }}"
                                                data-on-title = "{{translate('Want_to_Turn_ON_Deliveryman_Status').'?'}}"
                                                data-off-title = "{{translate('Want_to_Turn_OFF_Deliveryman_Status').'?'}}"
                                                data-on-message = "<p>{{translate('if_enabled_this_deliveryman_can_log_in_to_the_system_and_deliver_products')}}</p>"
                                                data-off-message = "<p>{{translate('if_disabled_this_deliveryman_cannot_log_in_to_the_system_and_deliver_any_products')}}</p>"
                                                data-on-button-text="{{ translate('turn_on') }}"
                                                data-off-button-text="{{ translate('turn_off') }}">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center align-items-center gap-10">
                                        <a  class="btn btn-outline-primary icon-btn edit" title="{{translate('edit')}}" href="{{route('admin.delivery-man.edit',[$deliveryMen['id']])}}">
                                            <i class="fi fi-rr-pencil"></i>
                                        </a>
                                        <a title="Earning Statement" class="btn btn-outline-info icon-btn" href="{{ route('admin.delivery-man.earning-statement-overview', ['id' => $deliveryMen['id']]) }}">
                                            <i class="fi fi-rr-sack-dollar"></i>
                                        </a>
                                        <a class="btn btn-outline-danger icon-btn delete delete-data" href="javascript:" data-id="delivery-man-{{$deliveryMen['id']}}" title="{{ translate('delete')}}">
                                            <i class="fi fi-rr-trash"></i>
                                        </a>
                                    </div>
                                    <form action="{{route('admin.delivery-man.delete',[$deliveryMen['id']])}}"
                                            method="post" id="delivery-man-{{$deliveryMen['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-end">
                        {!! $deliveryMens->links() !!}
                    </div>
                </div>
                @if(count($deliveryMens)==0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_delivery_man_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/deliveryman.js')}}"></script>
@endpush
