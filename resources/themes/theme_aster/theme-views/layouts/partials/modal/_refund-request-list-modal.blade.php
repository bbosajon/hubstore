<div class="modal fade" id="refund-modal" tabindex="-1">
    <div class="modal-dialog modal--lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body p-0 rtl">
                <div class="d-flex border-bottom justify-content-between gap-2 p-3">
                    <div>
                        <h3 class="mb-1 fs-22 fw-semibold">{{ translate('Refund Request') }}</h3>
                        <p class="mb-0 fs-14">{{ translate('Choose the item you’d like to refund.') }}</p>
                    </div>
                    <button class="close-custom-btn btn d-center border-0 fs-16 p-1 w-30 h-30 rounded-pill" type="button" data-bs-dismiss="modal" aria-label="Close">
                        <span class="top--02" aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="p-3 pt-0">
                    <div class="pt-xl-4 pt-3 px-xl-3 pb-xl-3">
                        <div class="card overflow-hidden rounded-10">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table m-0 align-middle table-borderless order-details-table">
                                        <thead class="table-light">
                                        <tr>
                                            <th class="border-0 text-capitalize">{{ translate('Product details') }}</th>
                                            <th class="border-0 text-center">{{ translate('Qty') }}</th>
                                            <th class="border-0 text-center text-capitalize">{{ translate('Total') }}</th>
                                            <th class="border-0 text-center text-capitalize">{{ translate('Action') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($order->details as $key => $detail)
                                            @php($product = $detail?->productAllStatus ?? json_decode($detail->product_details, true))
                                            @if($product)
                                                <?php
                                                    $refund_day_limit = getWebConfig(name: 'refund_day_limit');
                                                    $current = \Carbon\Carbon::now();
                                                    $length = $detail?->refund_started_at?->diffInDays($current);
                                                    ?>
                                                <tr>
                                                    <td>
                                                        <div class="media gap-3 align-items-center min-w-220">
                                                            <div class="avatar avatar-xxl rounded border overflow-hidden">
                                                                <img class="d-block img-fit"
                                                                     src="{{ getStorageImages(path: $detail?->productAllStatus?->thumbnail_full_url, type: 'product') }}"
                                                                     alt="{{ $product['name'] ?? '' }}"
                                                                     width="60">
                                                            </div>
                                                            <div class="media-body d-flex gap-1 flex-column">
                                                                <h6 class="fs-14 fw-semibold text-dark mb-1">
                                                                    <a href="#0" class="line--limit-1">
                                                                        {{ isset($product['name']) ? Str::limit($product['name'], 40) : '' }}
                                                                    </a>
                                                                </h6>
                                                                <div class="fs-12 text-capitalize text-muted">
                                                                    {{ translate('Unit price :') }}
                                                                    {{ webCurrencyConverter($detail->price) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">{{ $detail->qty }}</td>
                                                    <td class="text-center">
                                                        {{ webCurrencyConverter(($detail->qty * $detail->price) - $detail->discount) }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if($detail->refund_request != 0)
                                                            <button type="button"
                                                                    class="mx-auto btn btn-outline-primary py-2 px-3 rounded-2 fs-14 action-get-refund-details"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#refundRequestDetailsModal{{ $detail->id }}">
                                                                {{ translate('Refund Details') }}
                                                            </button>
                                                        @elseif($refund_day_limit > 0 && !is_null($length) && $length <= $refund_day_limit && $detail->refund_request == 0)
                                                            <button class="mx-auto btn btn-primary py-2 px-3 rounded-2 fs-14"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#refundModal{{ $detail->id }}">
                                                                {{ translate('Req. Refund') }}
                                                            </button>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@foreach ($order->details as $key => $detail)
    @php($product = $detail?->productAllStatus ?? json_decode($detail->product_details, true))
    @if($product)
        <?php
        $refund_day_limit = getWebConfig(name: 'refund_day_limit');
        $current = \Carbon\Carbon::now();
        $length = $detail?->refund_started_at?->diffInDays($current);
        ?>
        @if($detail->refund_request != 0)
        @include('theme-views.layouts.partials.modal._refund-request-details',['id'=>$detail->id,'order_details'=>$detail,'order'=>$order,'refund' => $detail?->refundRequest?->first(),'product'=>$product])
        @elseif($refund_day_limit > 0 && !is_null($length) && $length <= $refund_day_limit && $detail->refund_request == 0)
            @include('theme-views.layouts.partials.modal._refund',['id'=>$detail->id,'order_details'=>$detail,'order'=>$order,'product'=>$product])
        @endif
    @endif
@endforeach
