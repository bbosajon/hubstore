@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.admin.app')
@section('title', translate('add_new_notification'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/push_notification.png') }}" alt="">
                {{translate('send_notification')}}
            </h2>
        </div>
        <div class="row gx-2 gx-lg-3">
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.notification.index')}}" method="post" class="text-start form-advance-validation form-advance-inputs-validation form-advance-file-validation non-ajax-form-validate" novalidate="novalidate"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-3 mb-4">
                                <div class="col-md-6">
                                    <div class="h-100">
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="exampleFormControlInput1">{{translate('title')}} </label>
                                            <input type="text" name="title" class="form-control"
                                                   placeholder="{{translate('new_notification')}}"
                                                   required>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label"
                                                   for="exampleFormControlInput1">{{translate('description')}} </label>
                                            <textarea name="description" class="form-control text-area-max-min" rows="4" required></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-center align-items-center bg-section rounded-8 p-20 w-100 h-100">
                                        <div class="d-flex flex-column gap-30 w-100">
                                            <div class="text-center">
                                                <label for="" class="form-label fw-semibold mb-0">
                                                    {{translate('image')}} <span class="text-info-dark">({{ translate('Ratio') . " " . '1:1' }})</span>
                                                </label>
                                            </div>
                                            <div class="upload-file">
                                                <input type="file" name="image" class="upload-file__input single_file_input"
                                                       data-max-size="{{ getFileUploadMaxSize() }}"
                                                    accept="{{getFileUploadFormats(skip: '.svg,.gif')}}"  value="">
                                                <label
                                                    class="upload-file__wrapper">
                                                    <div class="upload-file-textbox text-center">
                                                        <img width="34" height="34" class="svg" src="{{dynamicAsset(path: 'public/assets/new/back-end/img/svg/image-upload.svg')}}" alt="image upload">
                                                        <h6 class="mt-1 fw-medium lh-base text-center">
                                                            <span class="text-info">{{ translate('Click to upload') }}</span>
                                                            <br>
                                                            {{ translate('or drag and drop') }}
                                                        </h6>
                                                    </div>
                                                    <img class="upload-file-img" loading="lazy" src="" data-default-src="" alt="">
                                                </label>
                                                <div class="overlay">
                                                    <div class="d-flex gap-10 justify-content-center align-items-center h-100">
                                                        <button type="button" class="btn btn-outline-info icon-btn view_btn">
                                                            <i class="fi fi-sr-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-info icon-btn edit_btn">
                                                            <i class="fi fi-rr-camera"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="fs-12 mb-0 text-center max-w-360 m-auto">
                                                {{ getFileUploadFormats(skip: '.svg, .gif', asBladeMessage: true).' '. translate('Image_size'). ' : '. translate('Max').' '. getFileUploadMaxSize() . 'MB' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end flex-wrap gap-3">
                                <button type="reset" class="btn btn-secondary">{{translate('reset')}} </button>
                                <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                        class="btn btn-primary {{env('APP_MODE')!='demo'?'':'call-demo-alert'}}">{{translate('send_Notification')}}  </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-20">
                        <div class="d-flex justify-content-between align-items-center gap-20 flex-wrap">
                            <h3 class="mb-0">
                                {{ translate('push_notification_table')}}
                                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">{{ $notifications->total() }}</span>
                            </h3>
                            <div class="flex-grow-1 max-w-300 min-w-100-mobile">
                                <form action="{{ url()->current() }}" method="get">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{translate('search_by_title')}}"
                                               aria-label="Search orders" value="{{ $searchValue }}" required>
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
                            <table class="table table-hover table-borderless align-middle">
                                <thead class="text-capitalize">
                                    <tr>
                                        <th>{{translate('SL')}} </th>
                                        <th>{{translate('title')}} </th>
                                        <th>{{translate('description')}} </th>
                                        <th>{{translate('image')}} </th>
                                        <th>{{translate('notification_count')}} </th>
                                        <th>{{translate('status')}} </th>
                                        <th>{{translate('resend')}} </th>
                                        <th class="text-center">{{translate('action')}} </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($notifications as $key=>$notification)
                                        <tr>
                                            <td>{{$notifications->firstItem()+ $key}}</td>
                                            <td>
                                                <span class="d-block">
                                                    {{Str::limit($notification['title'],30)}}
                                                </span>
                                            </td>
                                            <td>
                                                {{Str::limit($notification['description'],40)}}
                                            </td>
                                            <td>
                                                <img class="min-w-75" width="75" height="75"
                                                    src="{{ getStorageImages(path: $notification->image_full_url, type: 'backend-basic') }}" alt="">
                                            </td>
                                            <td id="count-{{$notification->id}}">{{ $notification['notification_count'] }}</td>
                                            <td>
                                                <form action="{{route('admin.notification.update-status')}}" method="post"
                                                    id="notification-status{{$notification['id']}}-form"
                                                    class="no-reload-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$notification['id']}}">
                                                    <label class="switcher mx-auto" for="notification-status{{$notification['id']}}">
                                                        <input
                                                            class="switcher_input custom-modal-plugin"
                                                            type="checkbox" value="1" name="status"
                                                            id="notification-status{{$notification['id']}}"
                                                            {{ $notification['status'] == 1 ? 'checked':'' }}
                                                            data-modal-type="input-change-form"
                                                            data-modal-form="#notification-status{{$notification['id']}}-form"
                                                            data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/notification-on.png') }}"
                                                            data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/notification-off.png') }}"
                                                            data-on-title = "{{translate('Want_to_Turn_ON_Notification_Status').'?'}}"
                                                            data-off-title = "{{translate('Want_to_Turn_OFF_Notification_Status').'?'}}"
                                                            data-on-message = "<p>{{translate('if_enabled_customers_will_receive_notifications_on_their_devices')}}</p>"
                                                            data-off-message = "<p>{{translate('if_disabled_customers_will_not_receive_notifications_on_their_devices')}}</p>"
                                                            data-on-button-text="{{ translate('turn_on') }}"
                                                            data-off-button-text="{{ translate('turn_off') }}">
                                                        <span class="switcher_control"></span>
                                                    </label>
                                                </form>
                                            </td>
                                            <td>
                                                <a href="javascript:" class="btn btn-outline-success icon-btn resend-notification"
                                                data-id="{{ $notification->id }}">
                                                    <i class="fi fi-rr-refresh"></i>
                                                </a>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-3">
                                                    <a class="btn btn-outline-primary icon-btn edit"
                                                    title="{{translate('edit')}}"
                                                    href="{{route('admin.notification.update',[$notification['id']])}}">
                                                        <i class="fi fi-sr-pencil"></i>
                                                    </a>
                                                    <a class="btn btn-outline-danger icon-btn delete-data-without-form"
                                                    title="{{translate('delete')}}"
                                                    data-action="{{route('admin.notification.delete')}}"
                                                    data-id="{{$notification['id']}}')">
                                                        <i class="fi fi-rr-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="mt-4">
                                <tfoot>
                                {!! $notifications->links() !!}
                                </tfoot>
                            </table>
                        </div>
                        @if(count($notifications) <= 0)
                            @include('layouts.admin.partials._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <span id="get-resend-notification-route-and-text" data-text="{{translate("resend_notification")}}" data-action="{{ route("admin.notification.resend-notification") }}"></span>
@endsection

@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/admin/notification.js')}}"></script>
@endpush
