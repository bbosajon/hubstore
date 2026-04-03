<div class="table-responsive">
    <table id="datatable"
        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
        <thead class="thead-light thead-50 text-capitalize">
            <tr>
                <th>{{ translate('SL') }}</th>
                <th>{{ translate('amount') }}</th>
                <th>{{ translate('request_time') }}</th>
                <th>{{ translate('Transaction_Note') }}</th>
                <th>{{ translate('status') }}</th>
                <th class="text-center">{{ translate('action') }}</th>
            </tr>
        </thead>
        <tbody>
            @if ($withdrawRequests->count() > 0)
                @foreach ($withdrawRequests as $key => $withdrawRequest)
                    <tr>
                        <td>{{ $withdrawRequests->firstitem() + $key }}</td>
                        <td>
                            {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $withdrawRequest['amount']), currencyCode: getCurrencyCode(type: 'default')) }}
                        </td>
                        <td>
                            {{ date('d M Y', strtotime($withdrawRequest->created_at)) }}
                            <br>
                            {{ date('h:i a', strtotime($withdrawRequest->created_at)) }}
                        </td>
                        <td>
                            <p class="max-w-400 text-wrap">{{ $withdrawRequest?->transaction_note ?? 'N/a' }}</p>
                        </td>
                        <td>
                            @if ($withdrawRequest->approved == 0)
                                <label class="badge badge-soft--primary border">{{ translate('pending') }}</label>
                            @elseif($withdrawRequest->approved == 1)
                                <label class="badge badge-soft-success border">{{ translate('approved') }}</label>
                            @else
                                <label class="badge badge-soft-danger border">{{ translate('denied') }}</label>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-3">
                                <button type="button" data-target="#withdrawInfoViewOffcanvas{{ $withdrawRequest['id'] }}" data-toggle="offcanvas"
                                        class="btn btn-outline-success icon-btn" title="{{translate('view')}}">
                                    <i class="fi fi-sr-eye d-flex"></i>
                                </button>
                                @if ($withdrawRequest->approved == 0)
                                    <button data-alert-title="{{ translate('you_want_to_delete_this_withdrawal_request') }}?"
                                    data-alert-text="{{ translate('once_deleted,_the_withdrawal_request_cannot_be_recovered_and_it_will_not_appear_in_your_withdrawal_request_method_list')}}." data-toggle="tooltip" title="{{ translate('Remove Withdraw Request') }}"
                                        class="btn btn-outline-danger icon-btn delete show-delete-data-alert"
                                        data-id="vendor-withdraw-delete-form-{{ $withdrawRequest['id'] }}">
                                        <i class="fi fi-rr-trash"></i>
                                    </button>
                                @endif
                            </div>
                            <form id="vendor-withdraw-delete-form-{{ $withdrawRequest['id'] }}" method="GET" style="display: none;"
                             action="{{ route('vendor.business-settings.withdraw.close', [$withdrawRequest['id']]) }}">
                                @csrf
                                @method('GET')
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@if ($withdrawRequests->total() <= 0)
    <div class="py-5">
        <div class="text-center">
            <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/not_data.svg') }}" alt="">
            <p class="text-muted mt-3">{{ translate('No_Request_Done_Yet') . '!' }}</p>
        </div>
    </div>
@endif

<div class="table-responsive mt-4">
    <div class="px-4 d-flex justify-content-lg-end">
        {{ $withdrawRequests->links() }}
    </div>
</div>
