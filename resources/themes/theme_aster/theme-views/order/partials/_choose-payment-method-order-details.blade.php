@php($customer_balance = auth('customer')->user()?->wallet_balance ?? 0)
@php($couponAmount = session()->has('coupon_discount') ? session('coupon_discount') : 0)
@php($totalAmount = $order['order_amount'] - $couponAmount)
@php($remain_balance = $customer_balance - $order['edit_due_amount'])
@php($walletInsufficient = ($customer_balance ?? 0) < ($order['edit_due_amount'] ?? 0))
@php($isPhysicalProduct = $order->details()->whereHas('product', fn ($q) => $q->where('product_type', 'physical'))->exists())
<div class="modal fade z-1049 order-choose-payment-method-modal" id="choosePaymentMethodModal-{{ $order['id'] }}"
     tabindex="-1"
     aria-labelledby="choosePaymentMethodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div id="global-loader" class="global-loader d-none">
                <span class="loader"></span>
            </div>
            <div class="cash-on-delivery-section">
                <form action="{{ route('customer.customer-order-edit-pay-amount') }}" method="POST"
                      class="needs-validation px-4" id="cash_on_delivery_form">
                    @csrf
                    <div class="modal-header border-0 p-2 d-block">
                        <div class="d-flex justify-content-end">
                            <button type="button"
                                    class="close-custom-btn btn d-center border-0 text-muted fs-12 p-1 w-30 h-30 lh-1 rounded-pill"
                                    data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-sr-cross d-flex"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div>
                            <div class="text-center mb-3">
                                <h4 class="fs-18 fw-bold text-center mb-2">{{ translate('Choose_Payment_Method') }}</h4>
                                <h6 class="text-muted mb-2">{{ translate('Due Bill') }}</h6>
                                <h3 class="fs-22">{{ webCurrencyConverter(amount: $order?->edit_due_amount ?? 0 ) }}</h3>
                            </div>

                            <div>
                                <ul class="option-select-btn d-grid flex-wrap gap-3">
                                    @if (isset($offlinePaymentMethods) && $offlinePaymentStatus['status'])
                                        <li>
                                            <label class="w-100 h-100 d-block cursor-pointer position-relative"
                                                   for="cash_on_delivery">
                                                <input type="radio" class="payment-radio" name="payment_method"
                                                       value="cash_on_delivery" id="cash_on_delivery">
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
                                                        <div class="collapse" id="bring_change_amount"
                                                             data-more="See More" data-less="See Less"
                                                             style="">
                                                            <div
                                                                class="bg-primary-op-05 border border-white rounded text-start p-3 mx-3 my-2">
                                                                <h6 class="fs-12 fw-semibold mb-1">
                                                                    {{ translate('Change Amount') }} ($)
                                                                </h6>
                                                                <p class="mb-2 fs-12 opacity-75 fw-normal text-transform-none">
                                                                    {{ translate('insert_amount_of_you_need_deliveryman_to_bring') }}
                                                                </p>
                                                                <input type="text"
                                                                       class="form-control only-integer-input-field"
                                                                       name="bring_change_amount_input"
                                                                       id="bring_change_amount_input"
                                                                       placeholder="Amount">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-center">
                                                        <a id="bring_change_amount_btn"
                                                           class="btn primary-color border-0 fs-12 text-center text-capitalize shadow-none base-color p-0 collapsed"
                                                           data-bs-toggle="collapse" href="#bring_change_amount"
                                                           role="button"
                                                           aria-expanded="false"
                                                           aria-controls="change_amount">{{ translate('See_More') }}</a>
                                                    </div>
                                                </div>
                                            </label>
                                        </li>
                                    @endif
                                    @if(getWebConfig(name: 'wallet_status') == 1)
                                        <li>
                                            <label class="w-100 h-100 d-block cursor-pointer position-relative"
                                                   for="wallet">
                                                <input type="radio" class="payment-radio" name="payment_method"
                                                       value="wallet" id="wallet">
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
                                            <div class="">
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
                                                    <input type="radio" id="pay_offline" name="online_payment"
                                                           data-theme="aster"
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
                                                <label
                                                    class="bg-white w-100 h-100 d-block position-relative cursor-pointer"
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
                    </div>
                    <div class="modal-footer">
                        <div class="d-flex justify-content-center align-items-center gap-3 w-100">
                            <button type="button" data-bs-dismiss="modal"
                                    class="btn btn--reset fw-semibold w-100">{{ translate('Cancel') }}</button>
                            <button type="submit"
                                    class="btn btn-primary fw-semibold w-100 payment-proceed-btn">{{ translate('Proceed_To_Pay') }}</button>
                        </div>
                    </div>
                </form>
            </div>
            @if (isset($offlinePaymentMethods) && $offlinePaymentStatus['status'])
                <div class="offline-payment-section d-none">
                    <div class="modal-header border-0 p-2 pb-0">
                        <div class="w-100 d-flex justify-content-between align-items-start gap-3">
                            <button type="button"
                                    class="btn p-0 bg-transparent border-0 shadow-none text-primary fw-semibold fs-18 lh-1 back-to-order back-to-cod px-2 pt-2"
                                    data-theme="aster">
                                <i class="fi fi-sr-angle-left d-flex"></i> {{ translate('Go_Back') }}
                            </button>
                            <button type="button"
                                    class="close-custom-btn btn d-center border-0 text-muted fs-12 p-1 w-30 h-30 lh-1"
                                    data-bs-dismiss="modal" aria-label="Close">
                                <i class="fi fi-sr-cross d-flex"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-body px-sm-4 pt-0">
                        <form action="{{ route('customer.customer-order-edit-pay-amount') }}" method="post"
                              class="needs-validation form-loading-button-form px-4">
                            @csrf
                            <input type="hidden" name="payment_method" value="offline">
                            <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                            <input type="hidden" name="payment_platform" value="web">
                            <div class="d-flex justify-content-center mb-2">
                                <img width="52"
                                     src="{{ dynamicAsset(path: 'public/assets/front-end/img/select-payment-method.png') }}"
                                     alt="">
                            </div>
                            <p class="fs-14 text-center">
                                {{ translate('pay_your_bill_using_any_of_the_payment_method_below_and_input_the_required_information_in_the_form') }}
                            </p>

                            <select class="form-select custom-select pay_offline_method"
                                    id="pay_offline_method_{{ $order['id'] }}" data-order-id="{{ $order['id'] }}"
                                    data-edit-due="{{ $order['edit_due_amount'] }}" name="payment_by"
                                    required>
                                <option value="" disabled>{{ translate('select_Payment_Method') }}</option>
                                @foreach ($offlinePaymentMethods as $method)
                                    <option value="{{ $method->id }}">{{ translate('payment_Method') }} :
                                        {{ $method->method_name }}</option>
                                @endforeach
                            </select>
                            <div class="" id="payment_method_field_{{ $order['id'] }}">
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
