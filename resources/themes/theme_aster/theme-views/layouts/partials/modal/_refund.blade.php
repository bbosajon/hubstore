<div class="modal fade" id="refundModal{{$id}}" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header px-4">
                <h1 class="modal-title fs-5" id="refundModalLabel">{{translate('Refund_Request')}}</h1>
                <button type="button" class="close-custom-btn btn d-center border-0 fs-16 p-1 w-30 h-30 rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <span class="top--02" aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body px-4">
                <div class="pb-2">
                    <div class="media align-items-center gap-3 mb-20">
                        <div class="avatar avatar-xxl rounded border overflow-hidden">
                            <img class="d-block img-fit-contain"
                                 src="{{ getStorageImages(path: $detail?->productAllStatus?->thumbnail_full_url, type: 'product') }}"
                                 alt="" width="60">
                        </div>
                        <div class="media-body d-flex gap-1 flex-column">
                            <h6 class="text-truncate width--20ch">
                                <h6>
                                    <a href="{{route('product',[$product['slug']])}}" class="fs-18 fw-semibold">
                                        {{isset($product['name']) ? Str::limit($product['name'],40) : ''}}
                                    </a>
                                    @if($order_details->refund_request == 1)
                                        <small class="text-center mb-1 badge text-primary border-primary-1 text-bg-primary rounded-1 fw-normal fs-12 bg-opacity-10"> ({{translate('refund_pending')}}) </small> <br>
                                    @elseif($order_details->refund_request == 2)
                                        <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-success-1 text-bg-success text-success"> ({{translate('refund_approved')}}) </small> <br>
                                    @elseif($order_details->refund_request == 3)
                                        <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-danger-1 text-bg-danger text-danger"> ({{translate('refund_rejected')}}) </small> <br>
                                    @elseif($order_details->refund_request == 4)
                                        <small class="text-center mb-1 badge rounded-1 fw-normal fs-12 bg-opacity-10 border-danger-1 text-bg-danger text-danger"> ({{translate('refund_refunded')}}) </small> <br>
                                    @endif<br>
                                </h6>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="fs-14 text-muted">{{ translate('Price')}} : <span class="text-dark"> {{webCurrencyConverter($order_details->price)}}</span></span>
                                    <div class="border-lines"></div>
                                    <span class="fs-14 text-muted">{{ translate('Qty')}} : <span class="text-dark"> {{$order_details->qty}}</span></span>
                                </div>
                                @if($order_details->variant)
                                <small class="fs-14 text-muted">{{translate('variant')}} : <span class="text-dark">{{$order_details->variant}}</span> </small>
                                @endif
                            </h6>
                        </div>
                    </div>

                    @php($refundDetailsSummery = \App\Utils\OrderManager::getRefundDetailsForSingleOrderDetails(orderDetailsId: $order_details['id']))
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="section-bg-cmn2 rounded-2 p-xxl-4 p-3 border">
                                <div class="d-flex flex-column gap-2">
                                    <h6 class="fs-14 mb-1 text-dark">{{ translate('Price info')}} :</h6>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('Total price')}} :</span>
                                        <span class="fs-14 text-muted">{{ webCurrencyConverter(($order_details->qty * $order_details->price) - $order_details->discount) }}</span>
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
                                        <span class="fs-14 text-muted">{{webCurrencyConverter($refundDetailsSummery['sub_total']) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('Coupon_Discount') }} :</span>
                                        <span class="fs-14 text-muted">{{webCurrencyConverter($refundDetailsSummery['coupon_discount']) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between gap-1">
                                        <span class="fs-14 text-muted">{{ translate('referral_Discount') }} :</span>
                                        <span class="fs-14 text-muted">{{webCurrencyConverter($refundDetailsSummery['referral_discount']) }}</span>
                                    </div>
                                    <div class="d-flex align-items-center border-top pt-3 mt-2 justify-content-between gap-1">
                                        <span class="fs-16 fw-semibold text-dark"> {{ translate('Total_Refundable_Amount') }}</span>
                                        <span class="fs-18 fw-semibold text-dark">{{ webCurrencyConverter($refundDetailsSummery['total_refundable_amount']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="">
                                <form action="{{ route('refund-store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="section-bg-cmn2 rounded-2 p-xxl-4 p-3 border">
                                        <div class="form-group mb-3">
                                            <input type="hidden" name="order_details_id" value="{{$order_details->id}}">
                                            <input type="hidden" name="amount" value="{{ $refundDetailsSummery['total_refundable_amount'] }}">
                                            <label for="comment" class="fs-16 fw-semibold text-dark mb-2">{{translate('Give a refund reason')}} <span class="text-danger">*</span></label>
                                            <textarea name="refund_reason" class="form-control" rows="4"
                                                      placeholder="{{  translate('refund_reason')}}" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label class="fs-16 fw-semibold text-dark">{{translate('Upload images')}}</label>
                                            <div class="d-flex flex-column gap-2">
                                                <div class="coba_refund flex-wrap d-flex align-items-center gap-2"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-xxl-4 d-flex align-items-center justify-content-end gap-3 mt-3">
                                        <button type="button" class="btn min-w-120 fw-medium btn--reset m-0"
                                                data-bs-dismiss="modal">{{translate('Cancel')}}</button>
                                        <button type="submit" class="btn min-w-120 btn-primary m-0">{{translate('Send Request')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
