@php
    use App\Utils\Helpers;
    use App\Utils\OrderManager;
    use App\Utils\ProductManager;
@endphp
@extends('theme-views.layouts.app')
@push('css_or_js')
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/front-end/css/payment.css') }}">
@endpush
@section('title', translate('order_Details').' | '.$web_config['company_name'].' '.translate('ecommerce'))
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            @include('theme-views.users-profile.account-order-details._order-details-head',['order'=>$order])
                            @include('theme-views.layouts.partials.modal._refund-request-list-modal')
                            <div class="mt-4 card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        @php($digitalProduct = false)
                                        @foreach ($order->details as $key=>$detail)
                                            @if(isset($detail->product->digital_product_type))
                                                @php($digitalProduct = $detail->product->product_type === 'digital' ? true : false)
                                                @if($digitalProduct === true)
                                                    @break
                                                @else
                                                    @continue
                                                @endif
                                            @endif
                                        @endforeach
                                        <table class="table m-0 align-middle table-borderless order-details-table">
                                            <thead class="table-light">
                                            <tr>
                                                <th class="border-0 text-capitalize">{{ translate('product_details') }}</th>
                                                <th class="border-0 text-center">{{ translate('qty') }}</th>
                                                <th class="border-0 text-center text-capitalize">{{ translate('price') }}</th>
                                                <th class="border-0 text-center text-capitalize">{{ translate('discount') }}</th>
                                                <th class="border-0 text-end" {{ ($order->order_type == 'default_type' && $order->order_status=='delivered') ? 'colspan="2"':'' }}>{{ translate('Total') }}</th>
                                                <th class="border-0 text-center text-capitalize">{{ translate('action') }}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($order->details as $key=> $detail)
                                                @php($product = $detail?->productAllStatus ?? json_decode($detail->product_details, true))
                                                @if($product)
                                                    <tr>
                                                        <td>
                                                            <div class="media gap-3 align-items-center min-w-220">
                                                                <div
                                                                    class="avatar avatar-xxl rounded border overflow-hidden">
                                                                    <img class="d-block img-fit"
                                                                         src="{{ getStorageImages(path: $detail?->productAllStatus?->thumbnail_full_url, type: 'product') }}"
                                                                         alt="" width="60">
                                                                </div>
                                                                <div class="media-body d-flex gap-1 flex-column">
                                                                    <h6>
                                                                        <a href="{{route('product',[$product['slug']])}}">
                                                                            {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                                                        </a>
                                                                        @if($detail->refund_request == 1)
                                                                            <small> ({{ translate('refund_pending') }}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 2)
                                                                            <small> ({{ translate('refund_approved') }}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 3)
                                                                            <small> ({{ translate('refund_rejected') }}
                                                                                ) </small> <br>
                                                                        @elseif($detail->refund_request == 4)
                                                                            <small> ({{ translate('refund_refunded') }}
                                                                                ) </small> <br>
                                                                        @endif<br>
                                                                    </h6>
                                                                    @if($detail->variant)
                                                                        <small>
                                                                            {{ translate('variant') }}: {{ $detail->variant}}
                                                                        </small>
                                                                    @endif
                                                                    <div class="fs-12 text-capitalize">
                                                                        {{ translate('unit_price') }}: {{ webCurrencyConverter($detail->price) }}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">{{ $detail->qty}}</td>
                                                        <td class="text-center">
                                                            {{webCurrencyConverter($detail->price * $detail->qty)}}
                                                        </td>
                                                        <td class="text-center">{{webCurrencyConverter($detail->discount)}}</td>
                                                        <td class="text-end">{{webCurrencyConverter(($detail->qty*$detail->price)-$detail->discount)}}</td>
                                                        @php($length = $detail?->refund_started_at?->diffInDays($current_date))

                                                        <td>
                                                            @if(
                                                                ($order->order_type == 'default_type' && ($order->order_status=='delivered' || ($order->payment_status == 'paid' && $digitalProduct))) ||
                                                                ($order->order_type != 'default_type' && $order->order_status=='delivered')
                                                            )
                                                                <div class="d-flex justify-content-center gap-2">
                                                                    @if($order->order_type == 'default_type')
                                                                        @if($order->order_status=='delivered')
                                                                            <button
                                                                                class="btn border-0 outline-0 p-0 text-nowrap text-primary fs-14"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#reviewModal{{ $detail->id }}">
                                                                                @if (isset($detail->reviewData))
                                                                                    {{ translate('Update_Review') }}
                                                                                @else
                                                                                    {{ translate('Give_Review') }}
                                                                                @endif
                                                                            </button>
                                                                            @include('theme-views.layouts.partials.modal._review',['id'=>$detail->id,'order_details'=>$detail])
                                                                        @endif
                                                                    @else
                                                                        <label
                                                                            class="badge bg-info rounded-pill text-capitalize">{{ translate('POS_order') }}</label>
                                                                    @endif
                                                                    @if($detail?->product && $order->payment_status == 'paid' && $detail?->product->digital_product_type == 'ready_product')
                                                                        <a href="javascript:"
                                                                           class="btn bg-icon p-0 d-center w-30 h-30 rounded-1 text-primary digital-product-download"
                                                                           data-action="{{ route('digital-product-download', $detail->id) }}">
                                                                            <i class="bi bi-download fs-18"></i>
                                                                        </a>
                                                                    @elseif($detail?->product && $order->payment_status == 'paid' && $detail?->product->digital_product_type == 'ready_after_sell')
                                                                        @if($detail->digital_file_after_sell)
                                                                            <a href="javascript:"
                                                                               data-action="{{ route('digital-product-download', $detail->id) }}"
                                                                               class="btn bg-icon p-0 d-center w-30 h-30 rounded-1 text-primary digital-product-download"
                                                                               data-bs-toggle="tooltip"
                                                                               data-bs-placement="bottom"
                                                                               data-bs-custom-class="custom-tooltip"
                                                                               data-bs-title="{{ translate('download') }}">
                                                                                <i class="bi bi-download fs-18"></i>
                                                                            </a>
                                                                        @else
                                                                            <a href="javascript:"
                                                                               class="btn bg-icon p-0 d-center w-30 h-30 rounded-1 text-primary"
                                                                               data-bs-toggle="tooltip"
                                                                               data-bs-placement="bottom"
                                                                               data-bs-title="{{ translate('Admin hasn’t uploaded it yet') }}">
                                                                                <i class="bi bi-download fs-18"></i>
                                                                            </a>
                                                                        @endif
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <div class="text-center text-muted">--</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-3">
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <div class="card">
                                            @if($order->order_type == 'default_type')
                                                @php(
                                                    $shipping = isset($order->shipping_address_data)
                                                        ? (is_string($order->shipping_address_data)
                                                            ? json_decode($order->shipping_address_data)
                                                            : $order->shipping_address_data)
                                                        : null
                                                )

                                                @php(
                                                    $billing = isset($order->billing_address_data)
                                                        ? (is_string($order->billing_address_data)
                                                            ? json_decode($order->billing_address_data)
                                                            : $order->billing_address_data)
                                                        : null
                                                )

                                                @php(
                                                    $isSameAddress = $billing && $shipping &&
                                                        ($billing->address == $shipping->address) &&
                                                        ($billing->city == $shipping->city) &&
                                                        ($billing->zip == $shipping->zip)
                                                )

                                                <div class="card-body">
                                                    <div class="d-flex flex-column gap-xxl-4 gap-3">
                                                        @if($shipping)
                                                            <address class="m-0">
                                                                <div class="media gap-2 mb-2">
                                                                    <img width="20"
                                                                         src="{{ theme_asset('assets/img/icons/location.png') }}"
                                                                         class="dark-support" alt="">
                                                                    <div class="media-body">
                                                                        <div
                                                                            class="mb-0 fw-bold text-dark fs-14 text-capitalize">
                                                                            {{ translate('shipping_address') }}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <p class="m-0 fs-12 text-capitalize">
                                                                    <strong>{{ translate('name') }}</strong>: {{ $shipping->contact_person_name ?? '' }}
                                                                    <br>
                                                                    <strong>{{ translate('phone') }}</strong>: {{ $shipping->phone ?? '' }}
                                                                    <br>
                                                                    <strong>{{ translate('city') }}
                                                                        /{{ translate('zip') }}</strong>: {{ $shipping->city ?? '' }}
                                                                    , {{ $shipping->zip ?? '' }}<br>
                                                                    <strong>{{ translate('address') }}</strong>: {{ $shipping->address ?? '' }}
                                                                </p>
                                                            </address>
                                                        @endif
                                                        @if($isSameAddress || $billing)
                                                            <div class="border-bottom"></div>
                                                            <address class="m-0">
                                                                <div class="media gap-2 mb-2">
                                                                    <img width="20"
                                                                         src="{{ theme_asset('assets/img/icons/location.png') }}"
                                                                         class="dark-support" alt="">
                                                                    <div class="media-body">
                                                                        <div
                                                                            class="mb-0 fw-bold text-dark fs-14 text-capitalize">
                                                                            {{ translate('billing_address') }}
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                @if($isSameAddress)
                                                                    <div
                                                                        class="section-bg-cmn rounded-1 fs-12 text-dark py-2 px-3">
                                                                        {{ translate('Same as shipping address') }}
                                                                    </div>
                                                                @else
                                                                    <p class="m-0 fs-12 text-capitalize">
                                                                        <strong>{{ translate('name') }}</strong>: {{ $billing->contact_person_name ?? '' }}
                                                                        <br>
                                                                        <strong>{{ translate('phone') }}</strong>: {{ $billing->phone ?? '' }}
                                                                        <br>
                                                                        <strong>{{ translate('city') }}
                                                                            /{{ translate('zip') }}</strong>: {{ $billing->city ?? '' }}
                                                                        , {{ $billing->zip ?? '' }}<br>
                                                                        <strong>{{ translate('address') }}</strong>: {{ $billing->address ?? '' }}
                                                                    </p>
                                                                @endif
                                                            </address>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="card">
                                            <div class="card-body">
                                                @php($orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order))
                                                <div class="row justify-content-end px-xl-2 py-xl-1">
                                                    <div class="col-12">
                                                        <div class="d-flex flex-column gap-3 text-dark">
                                                            @if($order['edited_status'] == 1)
                                                                <div
                                                                    class="fs-14 text-dark p-10px rounded bg-primary bg-opacity-10">
                                                                    #{{ translate('Note') }}:
                                                                    {{ translate('Total bill has been updated after the edits.') }}
                                                                </div>
                                                            @endif
                                                            <div
                                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <div>{{ translate('Total_Item') }}</div>
                                                                <div>
                                                                    {{ $orderTotalPriceSummary['totalItemQuantity'] }}
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <div>{{ translate('item_Price') }}</div>
                                                                <div>
                                                                    {{ webCurrencyConverter(amount:  $orderTotalPriceSummary['itemPrice']) }}
                                                                </div>
                                                            </div>
                                                            <div
                                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <div>{{ translate('item_Discount') }}</div>
                                                                <div>
                                                                    -{{ webCurrencyConverter(amount: $orderTotalPriceSummary['itemDiscount']) }}
                                                                </div>
                                                            </div>

                                                            @if($order->order_type != 'default_type')
                                                                <div
                                                                    class="d-flex flex-wrap justify-content-between align-`item`s-center gap-2">
                                                                    <div
                                                                        class="text-capitalize">{{ translate('extra_discount') }}</div>
                                                                    <div>
                                                                        - {{ webCurrencyConverter(amount:  $orderTotalPriceSummary['extraDiscount']) }}
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div
                                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <div>{{ translate('subtotal') }}</div>
                                                                <div>
                                                                    {{ webCurrencyConverter(amount:  $orderTotalPriceSummary['subTotal']) }}
                                                                </div>
                                                            </div>

                                                            <div
                                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <div
                                                                    class="text-capitalize">{{ translate('coupon_discount') }}</div>
                                                                <div>
                                                                    - {{ webCurrencyConverter(amount:  $orderTotalPriceSummary['couponDiscount']) }}
                                                                </div>
                                                            </div>

                                                            @if($orderTotalPriceSummary['referAndEarnDiscount'] > 0)
                                                                <div
                                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                    <div class="text-capitalize">
                                                                        {{ translate('referral_discount') }}
                                                                    </div>
                                                                    <div>
                                                                        - {{ webCurrencyConverter(amount:  $orderTotalPriceSummary['referAndEarnDiscount']) }}
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div
                                                                class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                <div>{{ translate('tax_fee') }}</div>
                                                                <div>
                                                                    {{ webCurrencyConverter(amount:  $orderTotalPriceSummary['taxTotal']) }}
                                                                </div>
                                                            </div>

                                                            @if($order->order_type == 'default_type' && $order?->is_shipping_free == 0)
                                                                <div
                                                                    class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                    <div
                                                                        class="text-capitalize">{{ translate('shipping_fee') }}</div>
                                                                    <div>
                                                                        {{ webCurrencyConverter(amount:  $orderTotalPriceSummary['shippingTotal']) }}
                                                                    </div>
                                                                </div>
                                                            @endif

                                                                <div
                                                                    class="d-flex flex-wrap border-top pt-3 justify-content-between align-items-center gap-2">
                                                                    <h4 class="text-capitalize fs-18">
                                                                        {{ translate('total') }}
                                                                        <span class="fs-10 fw-medium">
                                                                            {{ $orderTotalPriceSummary['tax_model'] == 'include' ? '('.translate('Tax_:_Inc.').')' : '' }}
                                                                        </span>
                                                                    </h4>
                                                                    <h2 class="text-dark fs-22">
                                                                        {{ webCurrencyConverter(amount: $orderTotalPriceSummary['totalAmount']) }}
                                                                    </h2>
                                                                </div>

                                                                @if($order['edited_status'] == 1 && $order?->latestEditHistory)
                                                                    @if($order?->latestEditHistory?->order_due_amount > 0)
                                                                        @if($order?->latestEditHistory?->order_due_payment_status == 'paid')
                                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                                <div class="text-capitalize">
                                                                                    {{ translate('Paid Amount') }}
                                                                                </div>
                                                                                <div>
                                                                                    {{ webCurrencyConverter(
                                                                                        amount: $order?->latestEditHistory?->order_amount - $order?->latestEditHistory?->order_due_amount
                                                                                    ) }}
                                                                                </div>
                                                                            </div>

                                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                                <div class="text-capitalize">
                                                                                    {{ translate('Due Amount Paid By') }}
                                                                                    <span>
                                                                                        ({{ ucwords(str_replace('_', ' ', $order?->latestEditHistory?->order_due_payment_method)) }})
                                                                                    </span>
                                                                                </div>
                                                                                <div>
                                                                                    {{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_due_amount) }}
                                                                                </div>
                                                                            </div>

                                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                                <div class="text-capitalize fw-bold">
                                                                                    {{ translate('Total Paid Amount') }}
                                                                                </div>
                                                                                <div class="fw-bold">
                                                                                    {{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_amount) }}
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                                <h4 class="text-capitalize text-danger fs-18">
                                                                                    {{ translate('due_Amount') }}
                                                                                </h4>
                                                                                <h2 class="text-danger fs-22">
                                                                                    {{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_due_amount) }}
                                                                                </h2>
                                                                            </div>
                                                                        @endif
                                                                    @endif

                                                                    @if($order?->latestEditHistory?->order_return_amount > 0)
                                                                        @if($order?->latestEditHistory?->order_return_payment_status == 'returned')
                                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                                <div class="text-capitalize">
                                                                                    {{ translate('Paid Amount') }}
                                                                                </div>
                                                                                <div>
                                                                                    {{ webCurrencyConverter(
                                                                                        amount: $order?->latestEditHistory?->order_amount + $order?->latestEditHistory?->order_return_amount
                                                                                    ) }}
                                                                                </div>
                                                                            </div>

                                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                                <div class="text-capitalize fw-bold text-dark">
                                                                                    {{ translate('Returned by') }}
                                                                                    <span>
                                                                                    ({{ ucwords(str_replace('_', ' ', $order?->latestEditHistory?->order_return_payment_method)) }})
                                                                                </span>
                                                                                </div>
                                                                                <div class="fw-bold text-dark">
                                                                                    {{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_return_amount) }}
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                                <h4 class="text-capitalize text-dark fw-bold fs-18">
                                                                                    {{ translate('Amount To Return') }}
                                                                                </h4>
                                                                                <h2 class="text-dark fw-bold fs-22">
                                                                                    {{ webCurrencyConverter(amount: $order?->latestEditHistory?->order_return_amount) }}
                                                                                </h2>
                                                                            </div>
                                                                        @endif
                                                                    @endif
                                                                @endif

                                                                @if ($order->order_type == 'POS' || $order->order_type == 'pos')
                                                                    <hr class="m-0">
                                                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                        <div class="text-capitalize fw-bold">
                                                                            {{ translate('paid_amount') }}
                                                                        </div>
                                                                        <div class="fw-bold">
                                                                            {{ webCurrencyConverter(amount: $orderTotalPriceSummary['paidAmount']) }}
                                                                        </div>
                                                                    </div>

                                                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                                                        <div class="text-capitalize fw-bold">
                                                                            {{ translate('change_amount') }}
                                                                        </div>
                                                                        <div class="fw-bold">
                                                                            {{ webCurrencyConverter(amount: $orderTotalPriceSummary['changeAmount']) }}
                                                                        </div>
                                                                    </div>
                                                                @endif

                                                                @if ($order['order_status'] == 'pending' && $order['payment_method'] == 'cash_on_delivery')
                                                                    <button
                                                                        class="btn btn-sm text-capitalize bg-opacity-10 text-danger bg-danger mt-3 delete-action"
                                                                        data-action="{{ route('order-cancel', [$order->id]) }}"
                                                                        data-message="{{ translate('want_to_cancel_this_order') . '?' }}">
                                                                        {{ translate('cancel_order') }}
                                                                    </button>
                                                                @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <span class="get-payment-method-list" data-action="{{ route('pay-offline-method-list') }}"></span>
@endsection

@push('script')
    <script src="{{ theme_asset('assets/js/spartan-multi-image-picker.js') }}"></script>
    <script src="{{ theme_asset('assets/js/order-summary.js') }}"></script>
    <script src="{{ theme_asset('assets/js/payment-page.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/front-end/js/payment.js') }}"></script>

@endpush
