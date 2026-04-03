@php use App\Utils\Helpers; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('my_Order_List').' | '.$web_config['company_name'].' '.translate('ecommerce'))
@push('css_or_js')
    <link rel="stylesheet" href="{{ theme_asset(path: 'public/assets/front-end/css/payment.css') }}">
@endpush
@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5 class="text-capitalize">
                                    {{ translate('my_order_list') }}
                                    @if($orders->count() > 0)
                                        <span>({{ $orders->count() }})</span>
                                    @endif
                                </h5>
                                <div class="border rounded  custom-ps-3 py-2">
                                    <div class="d-flex gap-2">
                                        <div class="flex-middle gap-2">
                                            <i class="bi bi-sort-up-alt"></i>
                                            <span
                                                class="d-none d-sm-inline-block text-capitalize">{{translate('show_order').':'}}</span>
                                        </div>
                                        <div class="dropdown">
                                            <button type="button"
                                                    class="border-0 bg-transparent dropdown-toggle text-dark p-0 custom-pe-3"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                {{translate($order_by=='asc'?'old':'latest')}}
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="d-flex" href="{{route('account-oder')}}/?order_by=desc">
                                                        {{translate('latest')}}
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="d-flex" href="{{route('account-oder')}}/?order_by=asc">
                                                        {{translate('old')}}
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                @if($orders->count() > 0)
                                    <div class="table-responsive d-none d-sm-block">
                                        <table
                                            class="table my_orderlist__table table-borderless align-middle table-striped">
                                            <thead class="text-primary border-bottom-2-white">
                                            <tr>
                                                <th class="bg-body ">{{translate('SL')}}</th>
                                                <th class="bg-body text-capitalize">{{translate('order_details')}}</th>
                                                <th class="bg-body text-center">{{translate('status')}}</th>
                                                <th class="bg-body text-center">{{translate('amount')}}</th>
                                                <th class="bg-body text-center">{{translate('action')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($orders as $key=>$order)
                                                <tr>
                                                    <td>{{ $orders->firstItem() + $key }}</td>
                                                    <td>
                                                        <div class="media gap-3 align-items-center mn-w200">
                                                            <div
                                                                class="avatar rounded size-3-75rem aspect-1 overflow-hidden d-flex align-items-center">
                                                                @if($order->seller_is == 'seller')
                                                                    <img class="img-fit dark-support rounded" alt=""
                                                                         src="{{ getStorageImages(path:$order?->seller?->shop->image_full_url, type:'shop') }}">
                                                                @elseif($order->seller_is == 'admin')
                                                                    <img class="img-fit dark-support rounded" alt=""
                                                                         src="{{ getStorageImages(path: getInHouseShopConfig(key: 'image_full_url'), type:'shop') }}">
                                                                @endif
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="d-flex align-items-center gap-1">
                                                                    <a href="{{ route('account-order-details', ['id'=>$order->id]) }}">
                                                                        {{translate('order')}}
                                                                        #{{$order['id']}}
                                                                    </a>
                                                                    @if($order->edited_status == 1)
                                                                        <span
                                                                            class="d-flex align-items-center gap-1 edit-text fw-medium text-muted fs-14">
                                                                          ({{ translate('Edited') }})
                                                                            @if($order?->latestEditHistory?->order_due_payment_status == 'unpaid' && $order?->latestEditHistory?->order_due_payment_method != "offline_payment" && $order?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $order?->latestEditHistory?->order_due_amount > 0)
                                                                                <span class="lh-1"
                                                                                      data-bs-toggle="tooltip"
                                                                                      data-bs-placement="top"
                                                                                      data-bs-title="{{ translate('You will pay the due  amount') }} ">
                                                                                    <i class="fi fi-sr-usd-circle text-danger"></i>
                                                                                </span>
                                                                            @elseif($order?->latestEditHistory?->order_return_payment_status == 'pending' && $order?->latestEditHistory?->order_return_amount > 0)
                                                                                <span class="lh-1"
                                                                                      data-bs-toggle="tooltip"
                                                                                      data-bs-placement="top"
                                                                                      data-bs-title="{{ translate('Admin return the excess amount to you')}}">
                                                                                    <i class="fi fi-sr-usd-circle text-danger"></i>
                                                                                </span>
                                                                            @endif
                                                                        </span>
                                                                    @endif
                                                                </h6>
                                                                <div
                                                                    class="text-dark fs-12">{{count($order->details)}} {{translate('items')}}</div>
                                                                <p class="text-muted fs-12">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                            <span
                                                                class="badge rounded-1 fw-normal fs-12 bg-opacity-10 border-danger-1 text-bg-danger text-danger">
                                                            {{ translate($order['order_status']=='failed' ? 'Failed To Deliver' : $order['order_status']) }}
                                                        </span>
                                                        @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                            <span
                                                                class="badge rounded-1 fw-normal fs-12 bg-opacity-10 border-success-1 text-bg-success text-success">
                                                            {{ translate($order['order_status']=='processing' ? 'packaging' : $order['order_status']) }}
                                                        </span>
                                                        @else
                                                            <span
                                                                class="badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
                                                            {{ translate($order['order_status']) }}
                                                        </span>
                                                        @endif


                                                        @if($order->edited_status == 1 && $order->edit_due_amount > 0 && $order?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $order?->latestEditHistory?->order_due_payment_status == "unpaid")
                                                            <div class="text-danger mt-1">
                                                                {{ translate('Partially_Paid') }}
                                                            </div>
                                                        @elseif($order->edited_status == 1 && $order?->latestEditHistory?->order_due_payment_status == 'unpaid' && $order?->latestEditHistory?->order_due_amount > 0)
                                                            <div class="text-danger mt-1">
                                                                {{ translate($order?->latestEditHistory?->order_due_payment_status) }}
                                                            </div>
                                                        @else
                                                            <div
                                                                class="{{ $order['payment_status']=='unpaid' ? 'text-danger' : 'text-success' }} mt-1">
                                                                {{ translate($order['payment_status']) }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-dark fw-medium text-center">
                                                        @php($orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order))
                                                        {{ webCurrencyConverter(amount: $orderTotalPriceSummary['totalAmount']) }}
                                                    </td>
                                                    <td>
                                                        <div
                                                            class="d-flex justify-content-center gap-2 align-items-center">
                                                            @if($order->edited_status == 1 && $order?->latestEditHistory?->order_due_payment_status == 'unpaid' && $order?->latestEditHistory?->order_due_payment_method != "offline_payment" && $order?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $order?->latestEditHistory?->order_due_amount > 0)
                                                                <button type="button"
                                                                        class="btn btn-outline-warning btn-action choose-payment-method-modal-btn"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#choosePaymentMethodModal-{{ $order['id'] }}"
                                                                        title="{{ translate('Pay Due Amount') }}">
                                                                    <i class="fi fi-sr-usd-circle d-flex"></i>
                                                                </button>
                                                            @endif
                                                            <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
                                                               class="btn btn-outline-info btn-action"
                                                               title="{{ translate('View Details') }}"
                                                               onclick="event.stopPropagation();">
                                                                <i class="bi bi-eye-fill"></i>
                                                            </a>
                                                            <a href="{{ route('generate-invoice', [$order->id]) }}"
                                                               class="btn btn-outline-success btn-action"
                                                               title="{{ translate('Invoice Download') }}"
                                                               onclick="event.stopPropagation();">
                                                                <img
                                                                    src="{{ theme_asset('assets/img/svg/download.svg') }}"
                                                                    alt="" class="svg">
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="d-flex flex-column">
                                        @foreach($orders as $key=>$order)
                                            <div
                                                class="d-flex gap-2 justify-content-between py-2 border-bottom d-sm-none">
                                                <div class="media gap-2 mn-w200 get-view-by-onclick"
                                                     data-link="{{ route('account-order-details', ['id'=>$order->id]) }}">
                                                    <div class="avatar rounded size-3-75rem">
                                                        @if($order->seller_is == 'seller')
                                                            <img class="img-fit dark-support rounded" alt=""
                                                                 src="{{ getStorageImages(path: $order?->seller?->shop->image_full_url, type:'shop') }}">
                                                        @elseif($order->seller_is == 'admin')
                                                            <img class="img-fit dark-support rounded" alt=""
                                                                 src="{{ getStorageImages(path: getInHouseShopConfig(key: 'image_full_url'), type:'shop') }}">
                                                        @endif
                                                    </div>
                                                    <div class="media-body">
                                                        <h6 class="d-flex align-items-center gap-1">
                                                            {{translate('order').'#'}}{{$order['id']}}
                                                            @if($order->edited_status == 1)
                                                                <span
                                                                    class="d-flex align-items-center gap-1 edit-text fw-medium text-muted fs-14">
                                                                    ({{ translate('Edited') }})
                                                                    @if($order?->latestEditHistory?->order_due_payment_status == 'unpaid' && $order?->latestEditHistory?->order_due_payment_method != "offline_payment" && $order?->latestEditHistory?->order_due_payment_method != "cash_on_delivery" && $order?->latestEditHistory?->order_due_amount > 0)
                                                                        <span class="lh-1" data-bs-toggle="tooltip"
                                                                              data-bs-placement="top"
                                                                              data-bs-title="You will pay the due amount ">
                                                                            <i class="fi fi-sr-usd-circle text-danger"></i>
                                                                        </span>
                                                                    @endif
                                                                 </span>
                                                            @elseif($order->edited_status == 1)
                                                                <span
                                                                    class="d-flex align-items-center gap-1 edit-text fw-medium text-muted fs-14">
                                                                    ({{ translate('Edited') }})
                                                                    @if($order?->latestEditHistory?->order_return_payment_status == 'pending' && $order?->latestEditHistory?->order_return_amount > 0)
                                                                        <span class="lh-1" data-bs-toggle="tooltip"
                                                                              data-bs-placement="top"
                                                                              data-bs-title="{{ translate('Admin return the excess amount to you')}}">
                                                                        <i class="fi fi-sr-usd-circle text-danger"></i>
                                                                     </span>
                                                                    @endif
                                                                 </span>
                                                            @endif
                                                        </h6>
                                                        <div class="text-dark fs-12">
                                                            {{ count($order->details) }} {{ translate('items') }}
                                                        </div>
                                                        <div
                                                            class="text-muted fs-12">{{date('d M, Y h:i A',strtotime($order['created_at']))}}</div>
                                                        <div class="d-flex gap-2 align-items-center fs-12">
                                                            <div class="text-muted">{{ translate('price').':' }}</div>
                                                            <div
                                                                class="text-dark"> {{webCurrencyConverter($order['order_amount'])}}</div>
                                                        </div>
                                                        <div class="d-flex gap-2 align-items-center fs-12">
                                                            <div class="text-muted">{{ translate('status') }} :</div>
                                                            @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                                                <span class="text-center badge bg-danger rounded-pill">
                                                                {{translate($order['order_status'] =='failed' ? 'failed_to_Deliver' : $order['order_status'])}}
                                                            </span>
                                                            @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                                                <span class="text-center badge bg-success rounded-pill">
                                                                {{translate($order['order_status']=='processing' ? 'packaging' : $order['order_status'])}}
                                                            </span>
                                                            @else
                                                                <span class="text-center badge bg-info rounded-pill">
                                                                {{translate($order['order_status'])}}
                                                            </span>
                                                            @endif

                                                            @if($order->edited_status == 1 && $order?->latestEditHistory?->order_due_payment_status == 'unpaid' && $order?->latestEditHistory?->order_due_amount > 0)
                                                                <div class="text-danger">
                                                                    {{ translate($order?->latestEditHistory?->order_due_payment_status) }}
                                                                </div>
                                                            @else
                                                                <div
                                                                    class="{{ $order['payment_status']=='unpaid' ? 'text-danger':'text-dark' }}"> {{ translate($order['payment_status']) }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                @if($orders->count()==0)
                                    <div
                                        class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 mt-5 w-100">
                                        <img width="80" class="mb-3"
                                             src="{{ theme_asset('assets/img/empty-state/empty-order.svg') }}" alt="">
                                        <h5 class="text-center text-muted">
                                            {{ translate('You_have_not_any_order_yet') }}!
                                        </h5>
                                    </div>
                                @endif
                                @if($orders->count()>0)
                                    <div class="card-footer border-0">
                                        {{$orders->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if($orders->count() > 0)
            @foreach($orders as $key => $order)
                @include('theme-views.order.partials._choose-payment-method-order-details',[
               'order' => $order,
               'orderDueAmount' => $order?->latestEditHistory?->order_due_amount ?? 0,
               'paymentGatewayList' => $paymentGatewayList,
              ])
            @endforeach
        @endif
    </main>
    <?php
    $orderSuccessIds = session('order_success_ids') ?? [];
    if (!is_array($orderSuccessIds)) {
        $orderSuccessIds = [];
    }
    $isPlural = count($orderSuccessIds) > 1;
    session()->forget('order_success_ids');
    ?>
    @if($orderSuccessIds && auth('customer')->check())
        <div class="modal fade" id="order_successfully" tabindex="-1">
            <div class="modal-dialog modal--md modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body rtl">
                        <div class="d-flex justify-content-end pb-2">
                            <button class="btn-close outside opacity-100 mt-lg-0 mt-3 shadow top-0-lg" type="button"
                                    data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="text-center pb-3 pt-4 mt-xl-2">
                            <div class="mb-20">
                                <img width="56" height="56" class=""
                                     src="{{theme_asset(path: "/assets/img/icons/check-fill.png")}}" alt="">
                            </div>
                            <h6 class="mb-3 fs-18 fw-semibold">{{translate('Thank You For Your Purchase!')}}</h6>
                            <p class="fs-14 title-semidark mb-30">
                                {{ translate('We have received your order and will ship it shortly.') }} {{ translate('Your Order ID' . ($isPlural ? 's' : '')) }}
                                {{ implode(', ', $orderSuccessIds) }}
                                {{ translate('keep it handy for tracking.') }}
                            </p>
                            <div class="max-w-290 mx-auto">
                                <a href="{{ route('home') }}"
                                   class="btn min-h-45 w-100 py-2 fs-14 btn-primary text-capitalize px-4 rounded-10">
                                    {{ translate('Explore More Items') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/front-end/js/payment.js') }}"></script>
    @if($orderSuccessIds)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modalEl = document.getElementById('order_successfully');
                const orderModal = new bootstrap.Modal(modalEl, {
                    backdrop: 'static',
                    keyboard: false
                });
                orderModal.show();
                const closeBtn = document.getElementById('modal-close-btn');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function () {
                        setTimeout(() => {
                            orderModal.hide();
                        }, 600);
                    });
                }
            });
        </script>
    @endif
@endpush
