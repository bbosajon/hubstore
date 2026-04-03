@php use App\Utils\Helpers;use App\Utils\ProductManager; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('refund_Details').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-5">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            <div class="mb-3 pb-3 border-bottom">
                                <h3 class="modal-title fs-16 fw-bold text-capitalize"
                                    id="refundModalLabel">{{translate('refund_details')}}</h3>
                            </div>
                            <div class="modal-body">
                                <form action="#">
                                    <div class="">
                                        <div class="media align-items-center gap-3 mb-20">
                                            <div class="avatar avatar-xxl rounded border overflow-hidden">
                                                <img class="dark-support img-fit rounded" alt=""
                                                    src="{{ getStorageImages(path:$product->thumbnail_full_url, type: 'product') }}">
                                            </div>
                                            <div class="media-body d-flex gap-1 flex-column">
                                                <h6 class="text-truncate width--20ch">
                                                    <h6>
                                                        <a href="{{route('product',[$product['slug']])}}" class="fs-18 mb-1 fw-semibold">
                                                            {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                                        </a>
                                                        @if($order_details->refund_request == 1)
                                                            <small class="text-center mb-1 badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
                                                                {{translate('refund_pending')}} </small> <br>
                                                        @elseif($order_details->refund_request == 2)
                                                            <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-success-1 text-bg-success text-success">
                                                                {{translate('refund_approved')}} </small> <br>
                                                        @elseif($order_details->refund_request == 3)
                                                            <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-danger-1 text-bg-danger text-danger">
                                                                {{translate('refund_rejected')}} </small> <br>
                                                        @elseif($order_details->refund_request == 4)
                                                            <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-danger-1 text-bg-danger text-danger">
                                                                {{translate('refund_refunded')}} </small> <br>
                                                        @endif
                                                    </h6>
                                                    <div class="d-flex align-items-center gap-2 flex-wrap">
                                                        <span class="fs-14 text-muted">{{ translate('Unit Price')}} : <span class="text-dark"> {{webCurrencyConverter($order_details->price)}}</span></span>
                                                        <div class="border-lines"></div>
                                                        <span class="fs-14 text-muted">{{ translate('Qty')}} : <span class="text-dark"> {{webCurrencyConverter($order_details->qty)}}</span></span>
                                                    </div>
                                                    @if($order_details->variant)
                                                        <small class="fs-14 text-muted">
                                                            {{translate('variant').':'}}
                                                           <span class="text-dark"> {{$order_details->variant}} </span>
                                                        </small>
                                                    @endif</h6>
                                            </div>
                                        </div>
                                        @php($refundDetailsSummery = \App\Utils\OrderManager::getRefundDetailsForSingleOrderDetails(orderDetailsId: $order_details['id']))
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="section-bg-cmn2 rounded-2 p-xxl-4 p-3 border">
                                                    <div class="d-flex flex-column gap-2">
                                                        <h6 class="fs-14 mb-2 text-dark">{{ translate('Price info')}} :</h6>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <span class="fs-14 text-muted">{{ translate('Total price')}} :</span>
                                                            <span class="fs-14 text-muted">{{ translate('$ 1,400')}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <span class="fs-14 text-muted">{{ translate('Discount')}} :</span>
                                                            <span class="fs-14 text-muted">{{webCurrencyConverter($order_details->discount)}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <span class="fs-14 text-muted">{{ translate('Vat/Tax')}} :</span>
                                                            <span class="fs-14 text-muted">{{webCurrencyConverter($order_details->tax)}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <span class="fs-14 text-muted">{{ translate('Subtotal') }} :</span>
                                                            <span class="fs-14 text-muted">{{webCurrencyConverter($refundDetailsSummery['sub_total'])}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <span class="fs-14 text-muted">{{ translate('Coupon_Discount') }} :</span>
                                                            <span class="fs-14 text-muted">{{webCurrencyConverter($refundDetailsSummery['coupon_discount'])}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center justify-content-between gap-1">
                                                            <span class="fs-14 text-muted">{{ translate('referral_Discount') }} :</span>
                                                            <span class="fs-14 text-muted">{{webCurrencyConverter($refundDetailsSummery['referral_discount'])}}</span>
                                                        </div>
                                                        <div class="d-flex align-items-center border-top pt-3 mt-3 justify-content-between gap-1">
                                                            <span class="fs-16 fw-semibold text-dark"> {{ translate('Total_Refundable_Amount') }}</span>
                                                            <span class="fs-18 fw-semibold text-dark"> {{webCurrencyConverter($refundDetailsSummery['total_refundable_amount'])}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="section-bg-cmn2 rounded-2 p-xxl-4 p-3 border">
                                                    <div class="border-color3 rounded-2 p-20 mb-3">
                                                        <h6 class="mb-3 fs-14">{{translate('refund_reason')}}</h6>
                                                        <p>{{$refund['refund_reason']}}</p>
                                                    </div>
                                                    <div class="border-color3 rounded-2 p-20">
                                                        <h6 class="mb-3 fs-14">{{translate('Uploaded Image')}}</h6>
                                                        <div class="d-flex flex-column gap-2">
                                                            @if (count($refund->images_full_url)>0)
                                                                <div class="gallery custom-image-popup-init">
                                                                    @foreach ($refund->images_full_url as $key => $photo)
                                                                        <a href="{{ getStorageImages(path: $photo, type:'product') }}"
                                                                           class="custom-image-popup">
                                                                            <img alt="" class="img-w-h-70"
                                                                                src="{{ getStorageImages(path: $photo, type:'product') }}">
                                                                        </a>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p>{{ translate('no_attachment_found')}}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('script')
    <script>
        'use strict';
        getVariantPrice(".add-to-cart-details-form");
    </script>
@endpush
