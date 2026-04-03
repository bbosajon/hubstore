<?php

use App\Models\OrderDetail;
use App\Utils\Helpers;
use App\Utils\ProductManager;
use function App\Utils\order_status_history;

?>
@extends('theme-views.layouts.app')

@section('title', translate('Track_Order_Result ').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="card h-100">
                <div class="card-body py-4 px-sm-4">
                    <div class=" px-xxl-2 pt-xxl-2">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-md-3 gap-2 mb-4">
                            <div class="flex-grow-1">
                                <h5 class="mb-1 fs-16">{{translate('Order')}} #{{$orderDetails['id']}} </h5>
                                <p class="fs-14">{{date('d M, Y h:i A',strtotime($orderDetails->created_at))}}</p>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex gap-3 align-items-center mt-1">
                                    <p class="text-capitalize m-0 fs-14 fw-medium">{{ translate('Order status') }} :</p>
                                    @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                        <span
                                            class="text-center badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
                                        {{translate($orderDetails['order_status'] =='failed' ? 'failed_to_deliver' : $orderDetails['order_status'])}}
                                    </span>
                                    @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                        <span
                                            class="text-center badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
                                         {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                                    </span>
                                    @else
                                        <span
                                            class="text-center badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
                                          {{translate($orderDetails['order_status'])}}
                                    </span>
                                    @endif
                                </div>
                                <div class="d-flex gap-3 align-items-center mt-1">
                                    <p class="text-capitalize m-0 fs-14 fw-medium">{{ translate('Payment status') }}
                                        :</p>
                                    @if($orderDetails['payment_status']=="paid")
                                        <span
                                            class="text-center badge text-danger border-danger-1 text-bg-danger rounded-1 fw-normal fs-12 bg-opacity-10">
                                        {{ translate('paid') }}
                                    </span>
                                    @else
                                        <span
                                            class="text-center badge text-danger border-danger-1 text-bg-danger rounded-1 fw-normal fs-12 bg-opacity-10">
                                        {{ translate('unpaid') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <?php
                        $trackOrderArray = \App\Utils\OrderManager::getTrackOrderStatusHistory(
                            orderId: $orderDetails['id'],
                            isOrderOnlyDigital: $isOrderOnlyDigital
                        );

                        $statusIcons = [
                            'order_placed' => 'track-shopping-list.svg',
                            'order_confirmed' => 'track2.svg',
                            'preparing_for_shipment' => 'track3.svg',
                            'order_is_on_the_way' => 'track4.svg',
                            'order_delivered' => 'track8.svg',
                            'order_canceled' => null,
                            'order_returned' => null,
                            'order_failed' => null,
                        ];

                        $terminalStatuses = ['order_canceled', 'order_returned', 'order_failed'];
                        $activeTerminalStatus = null;

                        foreach ($terminalStatuses as $terminalStatus) {
                            if (isset($trackOrderArray['history'][$terminalStatus]) && $trackOrderArray['history'][$terminalStatus]['status']) {
                                $activeTerminalStatus = $terminalStatus;
                                break;
                            }
                        }

                        if ($trackOrderArray['is_digital_order']) {
                            $statusesToShow = ['order_placed', 'order_confirmed', 'order_delivered'];
                        } else {
                            $statusesToShow = ['order_placed', 'order_confirmed', 'preparing_for_shipment', 'order_is_on_the_way', 'order_delivered'];
                        }

                        if ($activeTerminalStatus) {
                            $statusesToShow[] = $activeTerminalStatus;
                        }
                        ?>

                        <div class="card py-2">
                            <div class="card-body p-4 ps-3">
                                <div class="traking-slide-wrap style-main d-flex justify-content-center direction-ltr">
                                    <ul class="traking-slide-nav nav d-flex flex-nowrap text-nowrap">
                                        @foreach($trackOrderArray['history'] as $statusKey => $statusData)
                                            @continue(!in_array($statusKey, $statusesToShow))

                                                <?php $isTerminalStatus = in_array($statusKey, $terminalStatuses); ?>

                                            <li class="traking-item {{ $statusData['status'] ? 'active' : '' }} text-center w-240 position-relative z-1">
                                                <div
                                                    class="state-img d-center rounded-10 w-40 h-40 section-bg-cmn2 mb-15 mx-auto">
                                                    @if($isTerminalStatus)
                                                        <i class="bi bi-x-circle-fill fs-20"></i>
                                                    @else
                                                        <img width="20" class="svg"
                                                             src="{{ theme_asset('assets/img/icons/' . $statusIcons[$statusKey]) }}"
                                                             alt="icon">
                                                    @endif
                                                </div>

                                                <div class="badge-check mb-15">
                                                    @if($isTerminalStatus)
                                                        <i class="bi bi-x-circle-fill fs-16"></i>
                                                    @else
                                                        <i class="bi bi-check-circle-fill fs-16"></i>
                                                    @endif
                                                </div>

                                                <div class="contents">
                                                    <h6 class="{{ $statusData['status'] ? 'text-dark' : 'text-muted' }} mb-1 fs-14">
                                                        {{ translate($statusData['label']) }}
                                                    </h6>

                                                    @if($statusData['date_time'])
                                                        <p class="fs-12 m-0">
                                                            {{ $statusData['date_time']->format('h:i A, d M Y') }}
                                                        </p>
                                                    @endif

                                                    @if($statusKey === 'order_is_on_the_way' && $statusData['status'] && !$trackOrderArray['is_digital_order'])
                                                        <p class="fs-12 mb-0 mt-1">{{ translate('Your deliveryman is coming') }}</p>
                                                    @endif

                                                    @if($isTerminalStatus && $statusData['status'])
                                                        <a href="#0" class="fs-12 text-primary mb-0 mt-1">
                                                            @if($statusKey === 'order_canceled')
                                                                {{ translate('Order has been canceled') }}
                                                            @elseif($statusKey === 'order_returned')
                                                                {{ translate('Order has been returned') }}
                                                            @elseif($statusKey === 'order_failed')
                                                                {{ translate('Order processing failed') }}
                                                            @endif
                                                        </a>
                                                    @endif
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <div class="arrow-area">
                                        <div class="button-prev align-items-center">
                                            <button type="button"
                                                    class="btn btn-click-prev mr-auto border-0 btn-primary rounded-circle p-2 d-center">
                                                <i class="bi bi-chevron-left fs-14 lh-1"></i>
                                            </button>
                                        </div>
                                        <div class="button-next align-items-center">
                                            <button type="button"
                                                    class="btn btn-click-next ms-auto border-0 btn-primary rounded-circle p-2 d-center">
                                                <i class="bi bi-chevron-right fs-14 lh-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4 pb-2">
                            <h4 class="text-center fs-18 text-uppercase mb-20">{{ translate('your_order') }}
                                #{{ $orderDetails['id'] }} {{ translate('is') }}
                                @if($orderDetails['order_status']=='failed' || $orderDetails['order_status']=='canceled')
                                    {{translate($orderDetails['order_status'] =='failed' ? 'Failed To Deliver' : $orderDetails['order_status'])}}
                                @elseif($orderDetails['order_status']=='confirmed' || $orderDetails['order_status']=='processing' || $orderDetails['order_status']=='delivered')
                                    {{translate($orderDetails['order_status']=='processing' ? 'packaging' : $orderDetails['order_status'])}}
                                @else
                                    {{translate($orderDetails['order_status'])}}
                                @endif
                            </h4>
                            <button class="btn btn-primary mx-auto"
                                    data-bs-toggle="modal"
                                    data-bs-target="#order_details">
                                <span
                                    class="media-body text-nowrap">{{translate('view_order_details')}}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $order = OrderDetail::where('order_id', $orderDetails->id)->get(); ?>
        <?php
        $showOnlyPaymentInfo = ($orderDetails->edited_status == 1 && ($orderDetails?->latestEditHistory?->order_due_payment_method == "offline_payment" || $orderDetails?->latestEditHistory?->order_due_payment_method == "cash_on_delivery" || $orderDetails?->latestEditHistory?->order_due_payment_status == "paid")) ||
            ($orderDetails->edited_status == 1 && $orderDetails?->latestEditHistory?->order_due_payment_status == 'unpaid' && $orderDetails?->latestEditHistory?->order_due_payment_method != "offline_payment" && $orderDetails?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $orderDetails?->latestEditHistory?->order_due_amount > 0) ||
            ($orderDetails->edited_status == 1 && $orderDetails?->latestEditHistory?->order_return_payment_status === 'pending') ||
            ($orderDetails->edited_status == 1 && $orderDetails?->latestEditHistory?->order_return_payment_status == "returned");


        $hasUnpaidDue =
            $orderDetails->edited_status == 1 &&
            ($orderEditPaymentHistory?->first()?->order_due_payment_status !== 'paid'
                && $orderEditPaymentHistory?->first()?->order_due_amount > 0);

        $filteredEditPaymentHistory = $orderEditPaymentHistory?->filter(function ($item) {
            return $item->order_due_payment_status === 'paid'
                || $item->order_return_payment_status === 'returned';
        }) ?? collect();
        ?>

        <div class="modal fade order-choose-payment-method-modal" id="order_details" tabindex="-1"
             aria-labelledby="order_details"
             data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div id="global-loader" class="global-loader d-none">
                        <span class="loader"></span>
                    </div>
                    <div class="modal-header align-items-start mx-3 border-0">
                       <div
                            class="d-flex w-100 flex-wrap me-4 align-items-center justify-content-between gap-md-3 gap-2 modal-header-section">
                            <div class="flex-grow-1">
                                <div class="mb-1">
                                    <h6 class="modal-title d-flex gap-2 align-items-center" id="reviewModalLabel">
                                <span
                                    class="fs-18 fw-bold">{{translate('order')}} #{{ $orderDetails['id']  }}</span>
                                        @if($orderDetails['edited_status'] == 1)
                                            <span class="fw-normal fs-12">({{ translate('Edited') }})</span>
                                        @endif
                                    </h6>

                                    @if ($order_verification_status && $orderDetails->order_type == "default_type")
                                        <h5 class="small">{{translate('verification_code')}}
                                            : {{ $orderDetails['verification_code'] }}</h5>
                                    @endif
                                </div>
                                <p class="fs-14">{{date('D, d M, Y ',strtotime($orderDetails['created_at']))}}</p>
                            </div>
                            <div class="d-flex flex-column gap-2">
                                <div class="d-flex gap-3 align-items-center mt-1">
                                    <p class="text-capitalize m-0 fs-14 fw-medium">{{ translate('Order status') }} :</p>
                                    <span
                                        class="text-center badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
                                {{translate($orderDetails['order_status'])}}
                            </span>
                                </div>
                                <div class="d-flex gap-3 align-items-center mt-1">
                                    <p class="text-capitalize m-0 fs-14 fw-medium">{{ translate('Payment status') }}
                                        :</p>
                                    @if($orderDetails['payment_status']=="paid")
                                        <span
                                            class="text-center badge text-success border-success-1 text-bg-success rounded-1 fw-normal fs-12 bg-opacity-10">{{ translate('paid') }}</span>
                                    @else
                                        <span
                                            class="text-center badge text-danger border-danger-1 text-bg-danger rounded-1 fw-normal fs-12 bg-opacity-10">{{ translate('unpaid') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <button type="button"
                                class="close-custom-btn btn d-center border-0 text-muted fs-12 p-1 w-30 h-30 lh-1 rounded-pill position-absolute top-0 end-0 m-2"
                                data-bs-dismiss="modal" aria-label="Close">
                            <i class="fi fi-sr-cross d-flex"></i>
                        </button>
                    </div>

                    <div class="modal-body px-sm-4 pt-0" id="order-details-section">
                        <div class="row g-3 mb-3">
                            @if(($orderDetails['payment_method'] == 'cash_on_delivery' || $orderDetails?->latestEditHistory?->order_due_payment_method == 'cash_on_delivery') && $orderDetails['bring_change_amount'] > 0)
                                <div class="col-md-12">
                                    <div class="__badge soft-primary py-2 fs-14 text-dark rounded">
                                        {{ translate('Please bring') }}
                                        <strong> {{ $orderDetails['bring_change_amount'] }} {{ $orderDetails['bring_change_amount_currency'] ?? '' }}</strong> {{ translate('in change when making the delivery') }}
                                    </div>
                                </div>
                            @endif
                            <div class="{{ $showOnlyPaymentInfo ? 'col-md-6' : 'col-md-12' }}">
                                <div class="h-100 bg-light rounded-10 p-3 p-sm-4">
                                    <div class="d-flex flex-column gap-2 h-100">
                                        <h6 class="fs-14 text-capitalize fw-semibold">{{ translate('Payment_info') }}</h6>
                                        <div class="d-flex flex-column gap-2 bg-white rounded py-3 px-3 h-100">
                                            <div class="fs-12 d-flex gap-2">
                                                <span class="text-muted">{{ translate('Payment_status') }} :</span>
                                                @if($orderDetails->edited_status == 1 && $orderDetails->edit_due_amount > 0 && $orderDetails?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $orderDetails?->latestEditHistory?->order_due_payment_status == "unpaid")
                                                    <span
                                                        class="text-success fw-semibold">{{ translate('Partially_Paid') }}</span>
                                                @else
                                                    <span
                                                        class="text-success fw-semibold">{{ translate($orderDetails['payment_status']) }}</span>
                                                @endif
                                            </div>
                                            <div class="fs-12 d-flex gap-2">
                                                <span class="text-muted">{{ translate('Amount') }} :</span>
                                                <span
                                                    class="text-dark fw-semibold">{{ webCurrencyConverter(amount: $orderDetails['init_order_amount']) }}</span>
                                            </div>
                                            <div class="fs-12 d-flex gap-2">
                                                <span class="text-muted">{{ translate('Payment_method') }} :</span>
                                                <span
                                                    class="text-dark fw-semibold">{{ translate($orderDetails['payment_method']) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if($orderDetails->edited_status == 1 && ($orderDetails?->latestEditHistory?->order_due_payment_method == "offline_payment" || $orderDetails?->latestEditHistory?->order_due_payment_method == "cash_on_delivery" || $orderDetails?->latestEditHistory?->order_due_payment_status == "paid"))
                                <div class="col-lg-6">
                                    <div class="h-100 bg-light rounded-10 p-3 p-sm-4">
                                        <div class="d-flex flex-column gap-2 h-100">
                                            <h6 class="fs-14 text-capitalize fw-semibold">{{ translate('Another Payment Info') }}</h6>

                                            <div class="d-flex flex-column gap-2 bg-white rounded py-3 px-3 fs-12">
                                                <div class="d-flex gap-2">
                                                    <span class="text-muted">{{ translate('Payment status') }} :</span>
                                                    <span
                                                        class="text-success fw-semibold">{{translate( $orderDetails?->latestEditHistory?->order_due_payment_status) }}</span>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <span class="text-muted">{{ translate('Due_amount') }} :</span>
                                                    <span class="text-dark fw-semibold">{{ webCurrencyConverter(amount: $orderDetails?->latestEditHistory?->order_due_amount)  }}  </span>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <span class="text-muted">{{ translate('Payment method') }} :</span>
                                                    <span class="text-dark fw-semibold">{{translate($orderDetails?->latestEditHistory?->order_due_payment_method) }}  </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @elseif($orderDetails->edited_status == 1 && $orderDetails?->latestEditHistory?->order_due_payment_status == 'unpaid' && $orderDetails?->latestEditHistory?->order_due_payment_method != "offline_payment" && $orderDetails?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $orderDetails?->latestEditHistory?->order_due_amount > 0)
                                <div class="col-lg-6">
                                    <div class="h-100 bg-light rounded-10 p-3 p-sm-4 text-center">
                                        <h4 class="fs-16 text-danger mb-2">{{ translate('Pay Due Bill') }}</h4>
                                        <h4 class="fs-16 text-dark fw-bold mb-2">{{ webCurrencyConverter($orderDetails['edit_due_amount']) }}</h4>
                                        <p class="fs-12 mb-3">{{ translate('after_editing_your_product_list,_the_order_total_has_increased._please_pay_the_amount_to_continue_processing_the_order.') }}</p>
                                        <button type="button"
                                                class="btn btn-primary lh-1 mx-auto pay-now-btn">{{ translate('Pay_Now') }}</button>
                                    </div>
                                </div>
                            @elseif($orderDetails->edited_status == 1 && $orderDetails?->latestEditHistory?->order_return_payment_status === 'pending' )
                                <div class="col-lg-6">
                                    <div class="h-100 bg-light rounded-10 p-3 p-sm-4 text-center">
                                        <h4 class="fs-16 text-danger mb-2">{{ translate('Amount_to_Be_Returned') }}</h4>
                                        <h4 class="fs-16 text-dark fw-bold mb-2">{{ webCurrencyConverter($orderDetails['edit_return_amount']) }}</h4>
                                        <p class="fs-12 mb-0">{{ translate('after_editing_your_product_list,_you_will_receive_this_amount._please_wait_for_the_admin_to_process_the_returned.') }}</p>
                                    </div>
                                </div>
                            @elseif($orderDetails->edited_status == 1 && $orderDetails?->latestEditHistory?->order_return_payment_status == "returned")
                                <div class="col-lg-6">
                                    <div class="h-100 bg-light rounded-10 p-3 p-sm-4 text-center">
                                        <h6 class="fs-14 d-flex w-100 justify-content-between gap-2 mb-2">
                                    <span
                                        class="text-capitalize text-dark fw-semibold">{{translate('Return_Payment_info')}}</span>
                                            <span
                                                class="fs-12 fw-semibold text-{{$orderDetails['payment_status'] == 'paid' ? 'success' : 'danger'}} text-capitalize">{{$orderDetails['payment_status']}}</span>
                                        </h6>
                                        <div class="fs-12 d-flex justify-content-start gap-2">
                                            <span class="text-muted text-capitalize">{{translate('Return_Amount')}} :</span>
                                            <span
                                                class="text-dark text-capitalize fw-semibold">{{ webCurrencyConverter(amount: $orderDetails?->latestEditHistory?->order_return_amount) }}</span>
                                        </div>
                                        <div class="fs-12 d-flex justify-content-start gap-2">
                                            <span class="text-muted text-capitalize">{{translate('Return_Payment_method')}} :</span>
                                            <span
                                                class="text-dark text-capitalize fw-semibold">{{ $orderDetails?->latestEditHistory?->order_return_payment_method }}</span>
                                        </div>
                                        <div class="bg-white py-2 px-2 rounded text-start fs-14">
                                            #Note :{{ $orderDetails?->latestEditHistory?->order_return_payment_note }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="product-table-wrap">
                            <div class="table-responsive">
                                <table
                                    class="table __table text-capitalize text-start table-align-middle min-w400 text-dark fs-12 text-nowrap">
                                    <thead class="light-box mb-3">
                                    <tr>
                                        <th>{{ translate('SL') }}</th>
                                        <th class="min-w-300">{{ translate('Item_List') }}</th>
                                        <th class="text-center">{{ translate('Qty') }}</th>
                                        <th class="text-end">{{ translate('Price') }}</th>
                                        <th class="text-end">{{ translate('Discount') }}</th>
                                        <th class="text-end">{{ translate('Total') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $sub_total = 0; ?>
                                    <?php $total_discount_on_product = 0; ?>
                                    @foreach($order as $key => $order_details)
                                            <?php $productDetails = $order_details?->product ?? json_decode($order_details->product_details); ?>
                                            <?php $subtotal = ($order_details['price'] * $order_details['qty']) - $order_details['discount']; ?>
                                        <tr>
                                            <td>{{ $key+1 }}</td>
                                            <td>
                                                <div class="media align-items-center gap-3">
                                                    <img
                                                        src="{{ getStorageImages(path: $order_details?->product?->thumbnail_full_url, type: 'product') }}"
                                                        class="rounded border w-50px aspect-1"
                                                        alt="{{ translate('product') }}">
                                                    <div>
                                                        <h6 class="title-color mb-2 fs-12 fw-semibold">{{ Str::limit($productDetails->name,50) }}</h6>
                                                        <small>{{ translate('unit_price') }}
                                                            : {{ webCurrencyConverter($order_details['price']) }}</small><br>
                                                        @if($order_details->variant)
                                                            <small>{{ translate('variation') }}
                                                                : {{ $order_details['variant'] }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">{{ $order_details->qty }}</td>
                                            <td class="text-end">{{ webCurrencyConverter($order_details['price']*$order_details['qty']) }}</td>
                                            <td class="text-end">{{ webCurrencyConverter($order_details['discount']) }}</td>
                                            <td class="text-end">{{ webCurrencyConverter($subtotal) }}</td>
                                        </tr>
                                            <?php $sub_total += $order_details['price'] * $order_details['qty']; ?>
                                            <?php $total_discount_on_product += $order_details['discount']; ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <?php
                        $total_shipping_cost = $orderDetails['shipping_cost'];
                        $extra_discount = $orderDetails['extra_discount_type'] == 'percent' ? ($sub_total / 100) * $orderDetails['extra_discount'] : $orderDetails['extra_discount'];
                        $coupon_discount = $orderDetails['discount_amount'] ?? 0;
                        ?>

                        <div class="light-box rounded-10 p-1">
                            <div class="table-responsive">
                                <table class="table table-borderless table-align-middle text-capitalize">
                                    <thead>
                                    <tr class="fs-14">
                                        <th>{{translate('sub_total')}}</th>
                                        @if ($orderDetails['order_type'] == 'default_type' && $orderDetails['is_shipping_free'] != 1)
                                            <th>{{translate('shipping')}}</th>
                                        @endif
                                        @if($orderDetails['tax_model'] == 'exclude')
                                            <th>{{translate('tax')}}</th>
                                        @endif
                                        <th>{{translate('discount')}}</th>
                                        <th>{{translate('coupon_discount')}}</th>
                                        @if ($orderDetails['order_type'] == 'POS')
                                            <th>{{translate('extra_discount')}}</th>
                                        @endif
                                        <th>{{translate('total')}} @if ($orderDetails->tax_model =='include')
                                                <small>({{translate('tax_incl.')}})</small>
                                            @endif</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="fs-14 fw-bold">
                                        <td>{{ webCurrencyConverter($sub_total) }}</td>
                                        @if ($orderDetails['order_type'] == 'default_type' && $orderDetails['is_shipping_free'] != 1)
                                            <td>{{ webCurrencyConverter($total_shipping_cost) }}</td>
                                        @endif
                                        @if($orderDetails['tax_model'] == 'exclude')
                                            <td>{{ webCurrencyConverter($orderDetails['total_tax_amount']) }}</td>
                                        @endif
                                        <td>-{{ webCurrencyConverter($total_discount_on_product) }}</td>
                                        <td>-{{ webCurrencyConverter($coupon_discount) }}</td>
                                        @if ($orderDetails['order_type'] == 'POS')
                                            <td>-{{ webCurrencyConverter($extra_discount) }}</td>
                                        @endif
                                        <td>{{ webCurrencyConverter($sub_total + $total_shipping_cost + $orderDetails['total_tax_amount'] - $total_discount_on_product - $coupon_discount - $extra_discount) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            @if($orderDetails['edited_status'] == 1)
                                <div
                                    class="p-2 p-sm-3 rounded text-center text-dark fs-14 bg-primary bg-opacity-10 mt-2 mx-2">
                                    #{{ translate('Note_:_Total_bill_has_been_updated_after_the_edits.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-body px-sm-4 pt-0 d-none" id="payment-method-section">
                        <div class="">
                            <button type="button" class="btn p-0 bg-transparent border-0 shadow-none text-primary fw-semibold fs-18 lh-1 back-to-order"
                                    data-theme="aster">
                                <i class="fi fi-sr-angle-left d-flex"></i> {{ translate('Go_Back') }}
                            </button>
                        </div>
                        @include('theme-views.order.partials._choose-payment-method-modal', ['order' => $orderDetails])
                    </div>
                    <!-- OFFLINE PAYMENT SECTION -->
                    @if (isset($offlinePaymentMethods) && $offlinePaymentStatus['status'])
                        <div class="modal-body px-5 pt-0 d-none" id="offline-payment-section">
                            <div class="">
                                <button type="button"
                                        class="btn p-0 bg-transparent border-0 shadow-none text-primary fw-semibold fs-18 lh-1 back-to-payment-method"
                                        data-theme="aster">
                                    <i class="fi fi-sr-angle-left d-flex"></i> {{ translate('Go_Back') }}
                                </button>
                            </div>
                            <form action="{{ route('customer.customer-order-edit-pay-amount') }}" method="post"
                                  class="needs-validation form-loading-button-form">
                                @csrf
                                <input type="hidden" name="payment_method" value="offline">
                                <input type="hidden" name="order_id" value="{{ $orderDetails['id'] }}">
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
                                        id="pay_offline_method_{{ $orderDetails['id'] }}"
                                        data-edit-due="{{ $orderDetails['edit_due_amount'] }}"
                                        data-order-id="{{ $orderDetails['id'] }}" name="payment_by" required>
                                    <option value="" disabled selected>{{ translate('select_Payment_Method') }}</option>
                                    @foreach ($offlinePaymentMethods as $method)
                                        <option value="{{ $method->id }}">{{ translate('payment_Method') }}
                                            : {{ $method->method_name }}</option>
                                    @endforeach
                                </select>
                                <div class="" id="payment_method_field_{{$orderDetails['id']}}"></div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade __sign-in-modal" id="digital-product-order-otp-verify-modal" tabindex="-1"
         aria-labelledby="digital_product_order_otp_verifyLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
    <span class="get-payment-method-list" data-action="{{ route('pay-offline-method-list') }}"></span>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/tracking-page.js') }}"></script>
    <script src="{{ theme_asset('assets/js/payment-page.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/front-end/js/payment.js') }}"></script>
@endpush
