
<?php
$isOrderOnlyDigital = true;
if ($order->details) {
    foreach ($order->details as $detail) {
        $product = json_decode($detail->product_details);
        if (isset($product->product_type) && $product->product_type == 'physical') {
            $isOrderOnlyDigital = false;
        }
    }
}

use Carbon\Carbon;

$isEligibleForRefundButtonShow = 0;
$refund_day_limit = getWebConfig(name: 'refund_day_limit');
$current = Carbon::now();
foreach ($order->details as $key => $detail) {
    $product = $detail?->productAllStatus ?? json_decode($detail->product_details, true);
    if ($product) {
        $length = $detail?->refund_started_at?->diffInDays($current);
        if ($order->order_type == 'default_type' && $order->order_status == 'delivered') {
            if ($detail->refund_request != 0) {
                $isEligibleForRefundButtonShow++;
            }
            if ($refund_day_limit > 0 && !is_null($length) && $length <= $refund_day_limit && $detail->refund_request == 0) {
                $isEligibleForRefundButtonShow++;
            }
        }
    }
}
?>


<div class="border-bottom d-flex align-items-center justify-content-between flex-wrap gap-3 pb-20 mb-20">
    <div class="d-flex align-items-center gap-2 flex-wrap">
        <div class="d-flex align-items-center gap-2">
            <img class="svg svg-dark-support" src="{{theme_asset(path: "/assets/img/icons/home-icon.svg")}}" alt="icon">
            <h6 class="text-capitalize fs-14">{{ $order?->seller?->shop?->name ?? '' }}</h6>
        </div>
        @if($order['order_status']=='failed' || $order['order_status']=='canceled')
            <span class="badge text-danger border-danger-1 text-bg-danger rounded-1 fw-normal fs-12 bg-opacity-10">
            {{ translate($order['order_status']=='failed' ? 'Failed To Deliver' : $order['order_status']) }}
        </span>
        @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
            <span class="badge text-success border-success-1 text-bg-success rounded-1 fw-normal fs-12 bg-opacity-10">
            {{ translate($order['order_status']=='processing' ? 'packaging' : $order['order_status']) }}
        </span>
        @else
            <span class="badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
            {{ translate($order['order_status']) }}
        </span>
        @endif

    </div>
    <div class="d-flex align-items-center gap-xl-2 gap-2">
        @if($isEligibleForRefundButtonShow > 0)
            <button class="btn btn-outline-primary px-3 rounded-10 fw-medium" data-bs-toggle="modal"
            data-bs-target="#refund-modal">{{ translate('refund') }}</button>
        @endif
        @if($order->order_status=='delivered' &&  $order->order_type == 'default_type')
            <a href="javascript:" class="btn btn-primary px-3 rounded-10 fw-medium order-again"
               data-action="{{route('cart.order-again')}}"
               data-order-id="{{$order['id']}}">{{ translate('reorder') }}</a>
        @endif
        <a target="_blank" href="{{route('generate-invoice',[$order->id])}}"
           class="btn btn--reset px-3 rounded-10 fw-semibold"
           data-bs-toggle="tooltip"
           data-bs-placement="bottom"
           data-bs-title="{{ translate('download_invoice') }}">
            <i class="fi fi-rr-file-download"></i> {{ translate('Invoice') }}
        </a>
    </div>
</div>

@php
    $showVerificationCode = $order->order_type == 'default_type' && getWebConfig(name: 'order_verification') && $order['order_status'] != "delivered";
@endphp
<div>
    <div class="row g-3">

        <!-- Order Edit -->
        @if(($order['payment_method'] == 'cash_on_delivery' || $order?->latestEditHistory?->order_due_payment_method == 'cash_on_delivery') && $order['bring_change_amount'] > 0)
        <div class="col-md-12">
            <div class="__badge soft-primary py-2 fs-14 text-dark rounded w-100">
                {{ translate('Please bring') }}
                <strong> {{ $order['bring_change_amount'] }} {{ $order['bring_change_amount_currency'] ?? '' }}</strong> {{ translate('in change when making the delivery') }}
            </div>
        </div>
        @endif
        <div class="{{ $showVerificationCode ? 'col-md-6' : 'col-md-12'}}">
            <div
                class="section-bg-cmn rounded-2 py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-md-3 gap-2 h-100">

                <h5 class="mb-0 fs-16">
                    {{translate('order').' #' }}{{$order['id']}}
                    @if($order['edited_status' ] == 1)
                        <span class="edit-text fw-medium text-muted fs-14">
                        (Edited)
                    </span>
                    @endif
                </h5>
                <p class="fs-14">{{date('d M, Y h:i A',strtotime($order->created_at))}}</p>
            </div>
        </div>
        @if($showVerificationCode)
            <div class="col-md-6">
                <div
                    class="section-bg-cmn rounded-2 py-3 px-3 d-flex flex-wrap align-items-center justify-content-between gap-md-3 gap-2 h-100">
                    <h6 class="mb-0">
                        <span>{{ translate('Order_verification_code') }}</span>
                    </h6>
                    <h3 class="text-primary mb-0">{{$order['verification_code']}}</h3>
                </div>
            </div>
        @endif

        <div class="col-12">

            @php
                $showOnlyPaymentInfo =
                ($order->edited_status == 1 && $order?->latestEditHistory?->order_due_payment_method == "cash_on_delivery") ||
                ($order->edited_status == 1 && $order->edit_due_amount > 0 &&  $order?->payment_method != "cash_on_delivery") ||
                ($order->edited_status == 1 && $order?->latestEditHistory?->order_return_payment_status == 'pending') ||
                ($order->edited_status == 1 && $order?->latestEditHistory?->order_due_payment_status == "paid") ||
                ($order->edited_status == 1 && $order?->latestEditHistory?->order_return_payment_status == "returned");
            @endphp

            <div class="h-100 section-bg-cmn rounded-2 p-3">
                <h5 class="text-capitalize mb-2">{{translate('payment_info')}}</h5>
                <div class="row g-4">
                    <div class="{{ $showOnlyPaymentInfo ? 'col-md-6' : 'col-md-12' }}">
                        <div class="d-flex flex-column gap-2 bg-white rounded py-3 px-3 h-100">
                            <div class="fs-12 d-flex justify-content-start gap-2">
                                <span class="text-muted text-capitalize">{{translate('payment_status')}} :</span>
                                @if($order->edited_status == 1 && $order->edit_due_amount > 0 && $order->latestEditHistory->order_due_payment_method != "cash_on_delivery" && $order->latestEditHistory->order_due_payment_status == "unpaid")
                                    <span
                                        class="text-success text-capitalize fw-semibold">{{ translate('Partially_Paid') }}</span>
                                @else
                                    <span
                                        class="text-{{$order['payment_status'] == 'paid' ? 'success' : 'danger'}} text-capitalize fw-semibold">{{$order['payment_status']}}</span>
                                @endif
                            </div>
                            <div class="fs-12 d-flex justify-content-start gap-2">
                                <span class="text-muted text-capitalize">{{translate('payment_by')}} :</span>
                                <span
                                    class="text-dark text-capitalize fw-semibold">{{translate($order['payment_method'])}}</span>
                            </div>
                            <div class="fs-12 d-flex justify-content-start gap-2">
                                <span class="text-muted text-capitalize">{{translate('Amount')}} :</span>
                                @if(($order['total_order_amount'] ?? 0) > 0)
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{ webCurrencyConverter(amount:  $order['total_order_amount'])  }}</span>
                                @else
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{ webCurrencyConverter(amount:  $order['order_amount'])  }}</span>
                                @endif
                            </div>
                            @if($order->payment_method == 'offline_payment' && isset($order->offlinePayments))
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <button type="button"
                                            class="btn btn--reset mt-1 rounded-pill btn-sm text-capitalize fs-12 fw-semibold"
                                            data-bs-toggle="modal" data-bs-target="#verificationModal">
                                        {{ translate('see_payment_details') }}
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($order->edited_status == 1 && ($order?->latestEditHistory?->order_due_payment_method == "offline_payment" || $order?->latestEditHistory?->order_due_payment_method == "cash_on_delivery" || $order?->latestEditHistory?->order_due_payment_status == "paid"))
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 bg-white rounded py-3 px-3 h-100">
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span class="text-capitalize text-dark">
                                        {{translate('Another_Payment_Info') }}
                                    </span>
                                    <span class="text-{{ $order?->latestEditHistory?->order_due_payment_status == 'paid' ? 'success' : 'danger'}} text-capitalize fw-semibold">
                                        {{ translate($order?->latestEditHistory?->order_due_payment_status) }}
                                    </span>
                                </div>
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span class="text-muted text-capitalize">
                                        {{ translate('Payment_method') }} :
                                    </span>
                                    <span class="text-dark text-capitalize fw-semibold">
                                        {{ translate($order?->latestEditHistory?->order_due_payment_method) }}
                                    </span>
                                </div>
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span class="text-muted text-capitalize">{{translate('Due_amount')}} :</span>
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_due_amount ?? 0) }}</span>
                                </div>
                                @if($order?->latestEditHistory?->order_due_payment_method == "offline_payment" && !empty($order?->latestEditHistory?->order_due_payment_info))
                                    <div class="fs-12 d-flex justify-content-start gap-2">
                                        <button type="button"
                                                class="btn btn--reset mt-1 rounded-pill btn-sm text-capitalize fs-12 fw-semibold"
                                                data-bs-toggle="modal" data-bs-target="#orderDuePaymentInfoModal">
                                            {{ translate('see_payment_details') }}
                                        </button>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @elseif($order->edited_status == 1 && $order->edit_due_amount > 0 &&  $order?->payment_method != "cash_on_delivery")
                        <div class="col-md-6">
                            <div>
                                <div
                                    class="d-flex flex-column text-center justify-content-between align-items-center gap-1 h-100 section-bg-cmn rounded-2 p-3">
                                    <h5 class="fs-16 mb-2 text-danger fw-semibold text-capitalize">{{translate('Pay_Due_Bill')}}</h5>
                                    <h5 class="fw-bold">{{ webCurrencyConverter(amount: $order['edit_due_amount']) }}</h5>
                                    <p class="fs-12">
                                        {{ translate('after_editing_your_product_list,_the_order_total_has_increased._please_pay_the_amount_to_continue_processing_the_order.') }}
                                    </p>
                                    <button type="button" class="btn btn-primary py-2 px-3 fw-semibold"
                                            data-bs-toggle="modal"
                                            data-bs-target="#choosePaymentMethodModal-{{ $order['id'] }}">
                                        {{ translate('Pay_Now') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    @elseif($order->edited_status == 1 && $order?->latestEditHistory?->order_return_payment_status == 'pending')
                        <div class="col-md-6">
                            <div
                                class="d-flex flex-column text-center justify-content-between align-items-center gap-1 h-100 section-bg-cmn rounded-2 p-3">
                                <h6 class="fs-16 mb-2 text-danger fw-semibold text-capitalize">{{translate('Amount_to_Be_Returned')}}</h6>
                                <h6>{{ webCurrencyConverter(amount: $order['edit_return_amount']) }}</h6>
                                <p class="fs-12">
                                    {{ translate('after_editing_your_product_list,_you_will_receive_this_amount._please_wait_for_the_admin_to_process_the_returned.') }}
                                </p>
                            </div>
                        </div>
                    @elseif($order->edited_status == 1 && ($order?->latestEditHistory?->order_due_payment_status == "paid" || $order?->latestEditHistory?->order_due_payment_status == "cash_on_delivery"))
                        <div class="col-md-6">
                            <div class="d-flex flex-column gap-2 bg-white rounded py-3 px-3 h-100">
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                        <span
                                            class="text-capitalize text-dark">{{translate('Another_Payment_Info')}}</span>
                                    <span
                                        class="text-{{ $order?->latestEditHistory?->order_due_payment_status == 'paid' ? 'success' : 'danger'}} text-capitalize fw-semibold">{{ $order['payment_status']}}</span>
                                </div>
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                        <span
                                            class="text-muted text-capitalize">{{translate('Payment_method')}} :</span>
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{translate($order?->latestEditHistory?->order_due_payment_method)}}</span>
                                </div>
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span class="text-muted text-capitalize">{{translate('Due_amount')}} :</span>
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_due_amount ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    @elseif($order->edited_status == 1 && $order?->latestEditHistory?->order_return_payment_status == "returned")
                        <div class="col-md-6">
                            <div
                                class="d-flex flex-column justify-content-between align-items-start gap-2 h-100 section-bg-cmn rounded-2 p-3">
                                <h6 class="fs-14 d-flex w-100 justify-content-between gap-2 mb-2">
                                    <span
                                        class="text-capitalize text-dark fw-semibold">{{translate('Return_Payment_info')}}</span>
                                </h6>
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span
                                        class="text-muted text-capitalize">{{translate('Payment_status')}} :</span>
                                    <span
                                        class="text-{{$order['payment_status'] == 'paid' ? 'success' : 'danger'}} text-capitalize fw-semibold">{{$order['payment_status']}}</span>
                                </div>
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span
                                        class="text-muted text-capitalize">{{translate('Return_Payment_method')}} :</span>
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{ $order?->latestEditHistory?->order_return_payment_method }}</span>
                                </div>
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span
                                        class="text-muted text-capitalize">{{translate('Return_Amount')}} :</span>
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_return_amount) }}</span>
                                </div>
                                <div class="bg-white py-2 px-2 rounded text-start fs-14">
                                    #Note :{{ $order?->latestEditHistory?->order_return_payment_note }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<div class="mt-4">
    <nav>
        <div class="nav nav-nowrap gap-3 gap-xl-4 nav--tabs hide-scrollbar">
            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
               class="{{Request::is('account-order-details')  ? 'active' :''}} text-capitalize">{{translate('order_summary')}}</a>
            <a href="{{ route('account-order-details-vendor-info', ['id'=>$order->id]) }}"
               class="{{Request::is('account-order-details-vendor-info')  ? 'active' :''}} text-capitalize">{{translate('vendor_info')}}</a>
            @if($order->order_type != 'POS')
                <a href="{{ route('account-order-details-delivery-man-info', ['id'=>$order->id]) }}"
                   class="{{Request::is('account-order-details-delivery-man-info')  ? 'active' :''}} text-capitalize">{{translate('delivery_man_info')}}</a>
                <a href="{{ route('account-order-details-reviews', ['id'=>$order->id]) }}"
                   class="{{ Request::is('account-order-details-reviews')  ? 'active' :''}} text-capitalize">
                    {{ translate('reviews') }}
                </a>
                <a href="{{route('track-order.order-wise-result-view',['order_id'=>$order['id']])}}"
                   class="{{Request::is('track-order/order-wise-result-view*')  ? 'active' :''}} text-capitalize">
                    {{ translate('track_order') }}
                </a>
            @endif
        </div>
    </nav>
</div>

@if($order->payment_method == 'offline_payment' && isset($order->offlinePayments))
    <div class="modal fade" id="verificationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0 rtl">
                    <div class="d-flex justify-content-end gap-2 p-2">
                        <button class="close-custom-btn btn d-center border-0 fs-16 p-1 w-30 h-30 rounded-pill"
                                type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="top--02" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="pt-0 px-3 px-sm-4 pb-4">
                        <h3 class="text-center mb-4">{{ translate('Payment_Verification') }}</h3>
                        <div class="d-flex flex-column gap-3">

                            @foreach ($order->offlinePayments->payment_info as $key=>$value)
                                @if ($key != 'method_id')
                                    <div class="fs-12 d-flex justify-content-start gap-2">
                                        <span class="text-muted text-capitalize">{{translate($key)}} :</span>
                                        <span class="text-dark text-capitalize fw-semibold"> {{$value ?? "N/a"}}</span>
                                    </div>
                                @endif
                            @endforeach
                            @if($order->payment_note)
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                    <span class="text-muted text-capitalize">{{translate('Payment_Note')}} :</span>
                                    <span
                                        class="text-dark text-capitalize fw-semibold">{{ $order->payment_note }} </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@if($order?->latestEditHistory?->order_due_payment_method === 'offline_payment' && !empty($order?->latestEditHistory?->order_due_payment_info))
    <div class="modal fade" id="orderDuePaymentInfoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0 rtl">
                    <div class="d-flex justify-content-end gap-2 p-2">
                        <button class="close-custom-btn btn d-center border-0 fs-16 p-1 w-30 h-30 rounded-pill"
                                type="button" data-bs-dismiss="modal" aria-label="Close">
                            <span class="top--02" aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="pt-0 px-3 px-sm-4 pb-4">
                        <h3 class="text-center mb-4">{{ translate('Payment_Info') }}</h3>
                        <div class="d-flex flex-column gap-3">
                            @foreach($order->latestEditHistory->order_due_payment_info as $key => $value)
                                <div class="fs-12 d-flex justify-content-start gap-2">
                                <span class="text-muted text-capitalize">
                                    {{ translate($key) }} :
                                </span>
                                    <span class="text-dark fw-semibold">
                                    {{ $value ?? 'N/A' }}
                                </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@include('theme-views.order.partials._choose-payment-method-order-details',[
  'order' => $order,
  'paymentGatewayList' => $paymentGatewayList,
 ])
