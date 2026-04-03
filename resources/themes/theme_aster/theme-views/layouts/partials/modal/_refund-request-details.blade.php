<div class="modal fade" id="refundRequestDetailsModal{{$id}}" tabindex="-1" role="dialog" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header px-4">
                <h1 class="modal-title fs-5" id="refundModalLabel">{{ translate('Refund_Request') }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </button>
            </div>

            <div class="modal-body px-4">
                <div class="pb-2">

                    <div class="media align-items-center gap-3 mb-20">
                        <div class="avatar avatar-xxl rounded border overflow-hidden">
                            <img class="dark-support img-fit rounded" alt=""
                                 src="{{ getStorageImages(path:$product?->thumbnail_full_url, type: 'product') }}">
                        </div>
                        <div class="media-body d-flex gap-1 flex-column">
                            <h6 class="text-truncate width--30ch">
                                <a href="{{ $product?->slug ? route('product', [$product->slug]) : '#' }}"
                                   class="fs-18 mb-1 fw-semibold">
                                    {{ $product?->name ? Str::limit($product->name, 40) : translate('product_not_found') }}
                                </a>
                                @if($order_details->refund_request == 1)
                                    <small class="text-center mb-1 badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10">
                                        {{ translate('refund_pending') }}
                                    </small><br>
                                @elseif($order_details->refund_request == 2)
                                    <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-success-1 text-bg-success text-success">
                                        {{ translate('refund_approved') }}
                                    </small><br>
                                @elseif($order_details->refund_request == 3)
                                    <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-danger-1 text-bg-danger text-danger">
                                        {{ translate('refund_rejected') }}
                                    </small><br>
                                @elseif($order_details->refund_request == 4)
                                    <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-danger-1 text-bg-danger text-danger">
                                        {{ translate('refund_refunded') }}
                                    </small><br>
                                @endif
                            </h6>

                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <span class="fs-14 text-muted">{{ translate('Unit Price') }} :
                                    <span class="text-dark">{{ webCurrencyConverter($order_details->price) }}</span>
                                </span>
                                <div class="border-lines"></div>
                                <span class="fs-14 text-muted">{{ translate('Qty') }} :
                                    <span class="text-dark">{{ $order_details->qty}}</span>
                                </span>
                            </div>

                            @if($order_details->variant)
                                <small class="fs-14 text-muted">
                                    {{ translate('variant') }} :
                                    <span class="text-dark">{{ $order_details->variant }}</span>
                                </small>
                            @endif
                        </div>
                    </div>

                    @php($refundDetailsSummery = \App\Utils\OrderManager::getRefundDetailsForSingleOrderDetails(orderDetailsId: $order_details['id']))
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="section-bg-cmn2 rounded-2 p-xxl-4 p-3 border">
                                <div class="d-flex flex-column gap-2">
                                    <h6 class="fs-14 mb-2 text-dark">{{ translate('Price info') }} :</h6>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('Total price') }} :</span>
                                        <span class="fs-14 text-muted"> {{ webCurrencyConverter(($order_details->qty * $order_details->price) - $order_details->discount) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('Discount') }} :</span>
                                        <span class="fs-14 text-muted">{{ webCurrencyConverter($order_details->discount) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('Vat/Tax') }} :</span>
                                        <span class="fs-14 text-muted">{{ webCurrencyConverter($order_details->tax) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('Subtotal') }} :</span>
                                        <span class="fs-14 text-muted">{{ webCurrencyConverter($refundDetailsSummery['sub_total']) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('Coupon_Discount') }} :</span>
                                        <span class="fs-14 text-muted">{{ webCurrencyConverter($refundDetailsSummery['coupon_discount']) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('referral_Discount') }} :</span>
                                        <span class="fs-14 text-muted">{{ webCurrencyConverter($refundDetailsSummery['referral_discount']) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center border-top pt-3 mt-3 justify-content-between gap-1">
                                        <span class="fs-16 fw-semibold text-dark">{{ translate('Total_Refundable_Amount') }}</span>
                                        <span class="fs-18 fw-semibold text-dark">{{ webCurrencyConverter($refundDetailsSummery['total_refundable_amount']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="section-bg-cmn2 rounded-2 p-xxl-4 p-3 border">
                                <div class="border-color3 rounded-2 p-20 mb-3">
                                    <h6 class="mb-3 fs-14">{{ translate('refund_reason') }}</h6>
                                    <p>{{ $refund['refund_reason'] ?? translate('no_reason_provided') }}</p>
                                </div>
                                <div class="border-color3 rounded-2 p-20">
                                    <h6 class="mb-3 fs-14">{{ translate('Uploaded Image') }}</h6>
                                    <div class="d-flex flex-column gap-2">
                                        @if (!empty($refund) && count($refund->images_full_url) > 0)
                                            <div class="gallery custom-image-popup-init d-flex flex-wrap gap-2">
                                                @foreach ($refund->images_full_url as $key => $photo)
                                                    <a href="{{ getStorageImages(path: $photo, type:'product') }}"
                                                       class="custom-image-popup">
                                                        <img alt="" class="img-w-h-70 rounded border"
                                                             src="{{ getStorageImages(path: $photo, type:'product') }}">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted mb-0">{{ translate('no_attachment_found') }}</p>
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
