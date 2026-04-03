@if ($method)
    <div class="payment-list-area p-0">
        <div class="p-3 my-3 rounded --bg-light-sky-blue">
            <h6 class="pb-2" style="color: {{$web_config['primary_color']}};">{{ $method['method_name'] }}</h6>
            <input type="hidden" value="offline_payment" name="payment_method">
            <input type="hidden" value="{{ $method['id'] }}" name="method_id">

            <div class="row">
                @foreach ($method['method_fields'] as $method_field)
                    <div class="col-6 pb-2">
                        <span>{{ translate($method_field['input_name']).' '.':'}}</span>
                        <span>{{ $method_field['input_data'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <h4 class="text-center py-3 fw-6 font-weight--600">
            {{ translate('amount').' '.':' }} {{ webCurrencyConverter($totalOfflineAmount) }}
        </h4>

        <div class="row">
            @foreach ($method['method_informations'] as $information)
                <div class="col-md-12 col-lg-6 mb-3">
                    <label style="font-weight: 600;">
                        {{ translate($information['customer_input']) }}
                        <span class="text-danger">{{ $information['is_required'] == 1?'*':''}}</span>
                    </label>
                    <input type="text" class="form-control" name="{{ $information['customer_input'] }}"
                           placeholder="{{ translate($information['customer_placeholder']) }}" {{ $information['is_required'] == 1?'required':''}}>
                </div>
            @endforeach

            <div class="col-12 mb-3">
                <label class="font-weight--600">{{translate('payment_note')}}</label>
                <textarea name="payment_note" id="" class="form-control"
                          placeholder="{{translate('payment_note')}}"></textarea>
            </div>
        </div>
    </div>

    <div class="d-flex gap-10 justify-content-end pt-4">
        <button type="button" class="btn btn-sm btn-danger form-cancel-button"
                data-bs-dismiss="modal">{{translate('close')}}</button>
        <button type="submit" class="btn btn-sm bg-primary px-4 form-loading-button">
            <span class="spinner-grow spinner-grow-sm" aria-hidden="true"></span>
            <span class="loading">{{ translate('Loading') }}...</span>
            <span class="default">{{ translate('submit') }}</span>
        </button>
    </div>

@else
    <div class="text-center py-5">
        <img class="pt-5" src="{{ theme_asset('assets/img/offline-payments-vectors.png') }}" alt="">
        <p class="py-2 pb-5 text-muted">{{ translate('select_a_payment_method first') }}</p>
    </div>
@endif
