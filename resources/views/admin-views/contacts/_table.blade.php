<div class="table-responsive">
    <table id="datatable"
           class="table table-hover table-borderless table-nowrap align-middle card-table w-100">
        <thead class="thead-light thead-50 text-capitalize">
        <tr>
            <th>{{translate('SL')}}</th>
            <th>{{translate('customer_Name')}}</th>
            <th>{{translate('contact_Info')}}</th>
            <th>{{translate('subject')}}</th>
            <th>{{translate('time_&_Date')}}</th>
            <th class="text-center">{{translate('reply_status')}}</th>
            <th class="text-center">{{translate('action')}}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contacts as $key => $contact)
            <tr>
                <td>{{$contacts->firstItem()+$key}}</td>
                <td>{{$contact['name']}}</td>
                <td>
                    <div>
                        <div>{{$contact['mobile_number']}}</div>
                        <div>{{$contact['email']}}</div>
                    </div>
                </td>
                <td class="text-wrap">{{$contact['subject']}}</td>
                <td class="text-wrap" title="{{date('d M,Y h:i A',strtotime($contact['created_at']))}}" data-bs-toggle="tooltip" data-custom-class="darker-tooltip">
                    <div class="line--limit-2 max-w-200">
                        {{date('d M,Y h:i A',strtotime($contact['created_at']))}}
                    </div>
                </td>
                <td class="text-center">
                    @if(empty($contact['reply']))
                        <span class="badge badge-info text-bg-info">{{translate('No')}} <i class="fi fi-rr-check"></i></span>
                    @else
                        <span class="badge text-bg-success badge-success">{{translate('Yes')}} <i class="fi fi-rr-check"></i></span>
                    @endif
                </td>
                <td>
                    <div class="d-flex gap-10 justify-content-center">
                        <a title="{{translate('view')}}"
                           class="btn btn-outline-info icon-btn"
                           href="{{route('admin.contact.view',$contact->id)}}">
                           <i class="fi fi-rr-eye"></i>
                        </a>
                        <a class="btn btn-outline-danger icon-btn delete delete-data-without-form"
                           data-id="{{$contact['id']}}"
                           data-action="{{route('admin.contact.delete')}}"
                           title="{{ translate('delete')}}">
                           <i class="fi fi-rr-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<div class="table-responsive mt-4">
    <div class="px-4 d-flex justify-content-lg-end">
        {{$contacts->links()}}
    </div>
</div>
@if(count($contacts)==0)
    <div class="text-center p-4">
        <img class="mb-3 w-160"
             src="{{dynamicAsset(path: 'public/assets/back-end/svg/illustrations/sorry.svg')}}"
             alt="{{translate('image_description')}}">
        <p class="mb-0">{{translate('no_data_to_show')}}</p>
    </div>
@endif
