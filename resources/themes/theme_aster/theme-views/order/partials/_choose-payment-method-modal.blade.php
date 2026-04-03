@php($customer_balance = auth('customer')->user()?->wallet_balance ?? 0)
@php($couponAmount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
@php($totalAmount = $order['order_amount'] - $couponAmount)
@php($remain_balance = $customer_balance - $order['edit_due_amount'])
@php($walletInsufficient = ($customer_balance ?? 0) < ($order['edit_due_amount'] ?? 0))
<form action="{{ route('customer.customer-order-edit-pay-amount') }}" method="POST"
      class="needs-validation px-4 pb-3" id="cash_on_delivery_form">
    @csrf
    <div>
        <div class="text-center mb-3">
            <h4 class="fs-18 fw-bold text-center mb-2">{{ translate('Choose_Payment_Method') }}</h4>
            <h6 class="text-muted mb-2">{{ translate('Due Bill') }}</h6>
            <h3 class="fs-22">{{ webCurrencyConverter(amount: $order?->edit_due_amount ?? 0 ) }}</h3>
        </div>

        <div>
            <ul class="option-select-btn d-grid flex-wrap gap-3">
                @if($isPhysicalProduct && $cashOnDeliveryStatus)
                    <li>
                        <label class="w-100 h-100 d-block cursor-pointer position-relative" for="cash_on_delivery">
                            <input type="radio" class="payment-radio" name="payment_method" value="cash_on_delivery"
                                   id="cash_on_delivery">
                            <div type="button" id="cod-for-cart"
                                 class="payment-method-active cod-for-cart position-relative z-10 payment-method payment-method_parent d-flex align-items-center overflow-hidden flex-column p-0 w-100">
                                <div class="m-0">
                                    <div class="d-flex align-items-center gap-3 pt-1">
                                        <img width="30" class="dark-support" alt=""
                                             src="{{ theme_asset('assets/img/icons/cash-on.png') }}">
                                        <span
                                            class="text-capitalize fs-16">{{ translate('Cash_on_delivery') }}</span>
                                    </div>
                                </div>
                                <div class="bring_change_amount_section w-100">
                                    <div class="collapse" id="bring_change_amount" data-more="See More"
                                         data-less="See Less"
                                         style="">
                                        <div
                                            class="bg-primary-op-05 border border-white rounded text-start p-3 mx-3 my-2">
                                            <h6 class="fs-12 fw-semibold mb-1">
                                                {{ translate('Change Amount') }} ($)
                                            </h6>
                                            <p class="mb-2 fs-12 opacity-75 fw-normal text-transform-none">
                                                {{ translate('insert_amount_of_you_need_deliveryman_to_bring') }}
                                            </p>
                                            <input type="text" class="form-control only-integer-input-field"
                                                   name="bring_change_amount_input"
                                                   id="bring_change_amount_input" placeholder="Amount">
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <a id="bring_change_amount_btn"
                                       class="btn primary-color border-0 fs-12 text-center text-capitalize shadow-none base-color p-0 collapsed"
                                       data-bs-toggle="collapse" href="#bring_change_amount" role="button"
                                       aria-expanded="false"
                                       aria-controls="change_amount">{{ translate('See_More') }}</a>
                                </div>
                            </div>
                        </label>
                    </li>
                @endif
                @if(getWebConfig(name: 'wallet_status') == 1 && $order['is_guest'] != 1  && auth('customer')->check() && $order['customer_id'] == auth('customer')->id())
                    <li>
                        <label class="w-100 h-100 d-block cursor-pointer position-relative" for="wallet">
                            <input type="radio" class="payment-radio" name="payment_method" value="wallet"
                                   id="wallet">
                            <div
                                class="payment-method payment-method_parent position-relative z-10 d-flex align-items-center gap-3 overflow-hidden w-100 pay-via-wallet  {{ $walletInsufficient ? 'wallet-disabled' : '' }}"
                                {!! $walletInsufficient
                                    ? 'data-bs-toggle="tooltip" data-bs-placement="top" title="' . translate('Insufficient wallet balance') . '"'
                                    : '' !!}
                                type="button">
                                <img width="30"
                                     src="{{ theme_asset('assets/img/icons/wallet.png') }}"
                                     class="dark-support" alt="">
                                <span class="fs-16">{{ translate('Wallet') }}</span>
                            </div>
                        </label>
                    </li>
                @endif

                @if(getWebConfig(name: 'wallet_status') == 1)
                    <li id="wallet-info-section" class="full-width mx-auto fs-12 text-primary d-none wallet-info-section">
                        <div>
                            {{ translate('You’re paying') }}
                            <strong>{{ webCurrencyConverter(amount: $order['edit_due_amount'] ?? 0) }}</strong>
                            {{ translate('from your wallet.') }}
                            {{ translate('Remaining wallet balance') }}
                            <strong>
                                {{ webCurrencyConverter(amount: $remain_balance ?? 0) }}
                            </strong>
                        </div>
                    </li>
                @endif
                @if (isset($offlinePaymentMethods) && $offlinePaymentStatus['status'])
                    <li>
                        <div class="bg-white w-100 h-100">
                            <label
                                class="payment-method payment-method_parent position-relative z-10 overflow-hidden w-100 h-100 position-relative"
                                for="pay_offline"
                                data-modal="#offline_payment_submit_button">
                                <input type="radio" id="pay_offline" name="online_payment" data-theme="aster"
                                       data-order-id="{{ $order['id'] }}"
                                       data-method-id="{{ $offlinePaymentMethods?->take(1)->first()?->id }}"
                                       data-edit-due-amount="{{ $order['edit_due_amount'] }}"
                                       class="payment-radio pay_offline"
                                       value="pay_offline">
                                <div class="d-flex align-items-center gap-3 w-100 h-100">
                                    <img width="30"
                                         src="{{ theme_asset('assets/img/icons/cash-payment.png') }}"
                                         class="dark-support" alt="">
                                    <span class="fs-16">{{ translate('Offline_payment') }}</span>
                                </div>
                            </label>
                        </div>
                    </li>
                @endif


                @if(getWebConfig(name: 'digital_payment')['status'])
                    <li>
                        <label id="digital-payment-btn" class="w-100">
                            <span
                                class="payment-method payment-method_parent position-relative z-10 d-flex align-items-center gap-3 pay-via-digital">
                                <img width="30"
                                     src="{{ theme_asset('assets/img/icons/degital-payment.png') }}"
                                     class="dark-support" alt="">
                                <span class="fs-16">{{ translate('Digital Payment') }}</span>
                            </span>
                        </label>
                    </li>
                    @foreach ($paymentGatewayList as $payment_gateway)
                        @php($additionalData = $payment_gateway['additional_data'] != null ? json_decode($payment_gateway['additional_data']) : [])
                            <?php
                            $gatewayImgPath = dynamicAsset(path: 'public/assets/back-end/img/modal/payment-methods/' . $payment_gateway->key_name . '.png');
                            if ($additionalData != null && $additionalData?->gateway_image && file_exists(base_path('storage/app/public/payment_modules/gateway_image/' . $additionalData->gateway_image))) {
                                $gatewayImgPath = $additionalData->gateway_image ? dynamicStorage(path: 'storage/app/public/payment_modules/gateway_image/' . $additionalData->gateway_image) : $gatewayImgPath;
                            }
                            ?>
                        <li id="digital-info-section" class="digital-info-section fade-slide">
                            <label class="bg-white w-100 h-100 d-block position-relative cursor-pointer"
                                   for="{{ $payment_gateway->key_name }}">
                                <input type="hidden" name="external_redirect_link"
                                       value="{{ route('web-payment-success') }}">
                                <input type="hidden" name="payment_platform"
                                       value="web">
                                <input type="hidden" value="{{ $order['id'] }}" name="order_id">
                                <input type="radio" id="{{ $payment_gateway->key_name }}"
                                       name="payment_method"
                                       class="payment-radio"
                                       value="{{ $payment_gateway->key_name }}" data-keep>
                                <div
                                    class="payment-method next-btn-enable d-flex align-items-center gap-3 digital-payment-card overflow-hidden w-100"
                                    type="button">
                                    <img width="100" class="dark-support" alt=""
                                         src="{{ $gatewayImgPath }}">
                                </div>
                            </label>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center gap-3 w-100 mt-4">
        <button type="button" data-bs-dismiss="modal"
                class="btn btn--reset fw-semibold w-100">{{ translate('Cancel') }}</button>
        <button type="submit"
                class="btn btn-primary fw-semibold w-100 payment-proceed-btn">{{ translate('Proceed_To_Pay') }}</button>
    </div>
</form>


