<div class="modal fade" id="orderEditPayDueBill" tabindex="-1"
     aria-labelledby="orderEditPayDueBill" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">

            <div class="modal-header border-0">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-5">

                <form action="{{ route('customer.customer-order-edit-pay-amount') }}" method="post">
                    @csrf
                    <div class="pb-4">
                        <h4 class="text-center">{{ translate('add_Fund_to_Wallet') }}</h4>
                        <p class="text-center">{{ translate('add_fund_by_from_secured_digital_payment_gateways') }}</p>
                        <input type="hidden" name="order_id" value="{{ $order?->id }}">
                        <input type="text" name="order_due_payment_note">
                        <input type="number"
                               class="h-70 form-control text-center text-24 rounded-10 fs-25-important light-placeholder"
                               id="add-fund-amount-input" value="{{ $order?->edit_due_amount ?? 0 }}" name="amount"
                               required
                               placeholder="{{ translate('ex') }}: {{ webCurrencyConverter(amount: 500) }}">
                        <input type="hidden" value="web" name="payment_platform" required>
                        <input type="hidden" value="{{ request()->url() }}"
                               name="external_redirect_link" required>
                    </div>

                    <div id="add-fund-list-area">
                        @if(count($paymentGatewayList) > 0)
                            <h6 class="mb-2">{{ translate('payment_Methods') }}
                                <small>({{ translate('faster_&_secure_way_to_pay_bill') }}
                                    )</small></h6>
                            <div class="gateways_list">

                                @forelse ($paymentGatewayList as $gateway)
                                    <label class="form-check form--check rounded">
                                        <input type="radio" class="form-check-input d-none"
                                               name="payment_method"
                                               value="{{ $gateway->key_name }}" required>
                                        <div class="check-icon">
                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                 fill="none"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="8" cy="8" r="8" fill="#1455AC"/>
                                                <path
                                                    d="M9.18475 6.49574C10.0715 5.45157 11.4612 4.98049 12.8001 5.27019L7.05943 11.1996L3.7334 7.91114C4.68634 7.27184 5.98266 7.59088 6.53004 8.59942L6.86856 9.22314L9.18475 6.49574Z"
                                                    fill="white"/>
                                            </svg>
                                        </div>
                                        @php( $payment_method_title = !empty($gateway->additional_data) ? (json_decode($gateway->additional_data)->gateway_title ?? ucwords(str_replace('_',' ', $gateway->key_name))) : ucwords(str_replace('_',' ', $gateway->key_name)) )
                                        @php( $payment_method_img = !empty($gateway->additional_data) ? json_decode($gateway->additional_data)->gateway_image : '' )
                                        <div
                                            class="form-check-label d-flex align-items-center">
                                            <img width="60" alt="{{ translate('payment') }}"
                                                 src="{{ getValidImage(path: 'storage/app/public/payment_modules/gateway_image/'.$payment_method_img, type:'banner') }}">
                                            <span
                                                class="ml-3">{{ $payment_method_title }}</span>
                                        </div>
                                    </label>
                                @empty

                                @endforelse
                            </div>
                            <div class="d-flex justify-content-center pt-2 pb-3">
                                <button type="submit" class="btn btn--primary w-75 mx-3"
                                        id="add_fund_to_wallet_form_btn">{{ translate('add_Fund') }}</button>
                            </div>
                        @else
                            <h6 class="small text-center">{{ translate('no_Payment_Methods_Gateway_found') }}</h6>
                        @endif
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
