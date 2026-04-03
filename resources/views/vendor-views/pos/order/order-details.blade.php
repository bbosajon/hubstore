@extends('layouts.vendor.app')

@section('title', translate('order_Details'))

@push('css_or_js')
    <link href="{{ dynamicAsset(path: 'public/assets/back-end/css/vendor-product.css') }}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">

        <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
            <h2 class="fs-20 mb-0 text-capitalize d-flex align-items-center gap-2">
                <img width="20" height="20" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/all-orders.png') }}" alt="">
                <span>{{translate('order_Details')}}</span>
            </h2>
            <div class="d-flex gap-1 align-items-center">
                <a href="{{ $previousOrder ?  route('vendor.orders.details', [$previousOrder['id']]) : 'javascript:' }}"
                   class="btn btn-circle text-primary bg-primary-light {{ $previousOrder ? '' : 'disabled opacity-40' }}">
                    <i class="fi fi-sr-angle-left d-flex"></i>
                </a>
                <a href="{{ $nextOrder ? route('vendor.orders.details', [$nextOrder['id']]) : 'javascript:' }}"
                   class="btn btn-circle text-primary bg-primary-light {{ $nextOrder ? '' : 'disabled opacity-40' }}">
                    <i class="fi fi-sr-angle-right d-flex"></i>
                </a>
            </div>
        </div>

        <div class="row g-2" id="printableArea">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-10 flex-md-nowrap justify-content-between mb-4">
                            <div class="d-flex flex-column gap-3 flex-1">
                                <h4 class="text-capitalize fs-16 fw-bold">
                                    {{ translate('Order_Details') }} #{{ $order['id'] ??  '' }}
                                    @if(($order['order_type'] ??  '') == 'POS')
                                        <span>({{ 'POS' }})</span>
                                    @endif
                                </h4>
                                <div class="fs-12">
                                    {{date('d M, Y , h:i A', strtotime($order['created_at'] ?? null))}}
                                </div>
                            </div>
                            <div class="text-sm-end flex-grow-1">
                                <div class="d-flex flex-wrap gap-2 justify-content-sm-end">
                                    <a class="btn btn--primary" target="_blank"
                                       href="{{ route('vendor.orders.generate-invoice',[$order['id'] ?? '']) }}">
                                        <img src="{{ dynamicAsset(path:  'public/assets/back-end/img/icons/uil_invoice.svg') }}"
                                             alt="" class="mr-1">
                                        {{ translate('print_Invoice') }}
                                    </a>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-3">
                                    <div class="order-status d-flex justify-content-sm-end gap-10 text-capitalize fs-12">
                                        <span class="title-color">{{ translate('status') }}: </span>
                                        @if(($order['order_status'] ?? '')=='pending')
                                            <span
                                                class="badge badge-soft-info font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',($order['order_status'] ?? ''))) }}
                                            </span>
                                        @elseif(($order['order_status'] ?? '')=='failed')
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',($order['order_status'] ?? ''))) }}
                                            </span>
                                        @elseif(($order['order_status'] ?? '')=='processing' || ($order['order_status'] ?? '')=='out_for_delivery')
                                            <span
                                                class="badge badge-soft-warning font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',($order['order_status'] ?? ''))) }}
                                            </span>
                                        @elseif(($order['order_status'] ?? '')=='delivered' || ($order['order_status'] ?? '')=='confirmed')
                                            <span
                                                class="badge badge-soft-success font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',($order['order_status'] ?? ''))) }}
                                            </span>
                                        @else
                                            <span
                                                class="badge badge-soft-danger font-weight-bold radius-50 d-flex align-items-center py-1 px-2">
                                                {{ translate(str_replace('_',' ',($order['order_status'] ??  ''))) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="payment-method d-flex justify-content-sm-end gap-10 text-capitalize fs-12">
                                        <span class="title-color">{{ translate('payment_Method') }} :</span>
                                        <strong>  {{ translate(str_replace('_',' ',($order['payment_method'] ??  ''))) }}</strong>
                                    </div>
                                    @if(isset($order['transaction_ref']) && (($order['payment_method'] ?? '') != 'cash_on_delivery') && (($order['payment_method'] ?? '') != 'pay_by_wallet') && ! isset($order['offline_payments']))
                                        <div
                                            class="reference-code d-flex justify-content-sm-end gap-10 text-capitalize fs-12">
                                            <span class="title-color">{{ translate('reference_Code') }} :</span>
                                            <strong>{{ translate(str_replace('_',' ',($order['transaction_ref'] ?? ''))) }} {{ (($order['payment_method'] ??  '') == 'offline_payment') ? '('. ($order['payment_by'] ?? '').')':'' }}</strong>
                                        </div>
                                    @endif
                                    <div class="payment-status d-flex justify-content-sm-end gap-10 fs-12">
                                        <span class="title-color">{{ translate('payment_Status') }}:</span>
                                        @if(($order['payment_status'] ?? '')=='paid')
                                            <span class="text-success font-weight-bold">
                                                {{ translate('paid') }}
                                            </span>
                                        @else
                                            <span class="text-danger font-weight-bold">
                                                {{ translate('unpaid') }}
                                            </span>
                                        @endif
                                    </div>
                                    @if(getWebConfig('order_verification') && (($order['order_type'] ?? '') == "default_type"))
                                        <span class="d-flex justify-content-sm-end gap-10 fs-12">
                                            <b>
                                                {{translate('order_verification_code')}} :  {{$order['verification_code'] ??  '' }}
                                            </b>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive datatable-custom">
                            <table
                                class="table fs-12 table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100">
                                <thead class="thead-light thead-50 text-capitalize">
                                <tr>
                                    <th>{{translate('SL')}}</th>
                                    <th>{{translate('item_details')}}</th>
                                    <th class="text-center">{{ translate('Qty') }}</th>
                                    <th class="text-end">{{translate('item_price')}}</th>
                                    <th class="text-end">{{translate('tax')}}</th>
                                    <th class="text-end">{{translate('discount')}}</th>
                                    <th class="text-end">{{translate('total_price')}}</th>
                                </tr>
                                </thead>

                                <tbody>
                                @php($itemPrice=0)
                                @php($subtotal=0)
                                @php($total=0)
                                @php($shipping=0)
                                @php($discount=0)
                                @php($extraDiscount=0)
                                @php($productPrice=0)
                                @php($totalProductPrice=0)
                                @php($couponDiscount=0)
                                @foreach(($order['details'] ?? []) as $key => $detail)

                                    <?php
                                        $isProductUnavailable = (($detail['productAllStatus'] ?? null) === null);
                                        $productDetails = json_decode($detail['product_details'] ?? null, true);
                                        if (! empty($detail['productAllStatus'])) {
                                            $getCurrentThumbnailImage = checkImageStatus(path: $detail['productAllStatus']['thumbnail'] ?? null, storagePath: $detail['productAllStatus']['thumbnail_storage_type'] ?? 'public');
                                        } else {
                                            $getCurrentThumbnailImage = checkImageStatus(path: $productDetails['thumbnail'] ?? null, storagePath: $productDetails['thumbnail_storage_type'] ?? 'public');
                                        }
                                    ?>

                                    @if($productDetails)
                                        <tr>
                                            <td>{{++$key}}</td>
                                            <td>
                                                <div  class="{{ $isProductUnavailable ? 'opacity-50 cus-disabled' : '' }}"  data-toggle="tooltip"  data-placement="bottom"
                                                      title="{{ $isProductUnavailable ? translate('This_product_has_been_deleted') : '' }}">
                                                    <div class="media align-items-center gap-10">
                                                        <img class="avatar avatar-60 rounded img-fit"
                                                             src="{{ getStorageImages(path: $getCurrentThumbnailImage, type:'backend-product') }}"
                                                             alt="{{translate('image_description')}}">
                                                        <div>
                                                            <a class="title-color fs-12" href="{{ route('vendor.products.view', [$productDetails['id'] ?? '']) }}"
                                                               @if(! $isProductUnavailable && strlen($productDetails['name'] ?? '') > 10)
                                                                   data-toggle="tooltip" title="{{ $productDetails['name'] ?? '' }}" @endif
                                                            >
                                                                {{ Str::limit($productDetails['name'] ?? '', 30) }}
                                                            </a>
                                                            <div class="fs-10">
                                                                <strong>{{ translate('unit_price') }} :</strong>
                                                                {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $detail['price'] ?? 0)) }}
                                                            </div>
                                                            @if (! empty($detail['variant'] ?? null))
                                                                <div class="max-w-150px text-wrap fs-10">
                                                                    <strong>{{ translate('variation') }} :</strong> {{$detail['variant'] ?? ''}}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if(isset($productDetails['digital_product_type']) && ($productDetails['digital_product_type'] ?? '') == 'ready_after_sell')
                                                        <button type="button" class="btn btn-sm btn--primary mt-2"
                                                                title="File Upload" data-toggle="modal"
                                                                data-target="#fileUploadModal-{{ $detail['id'] ??  '' }}">
                                                            <i class="tio-file-outlined"></i> {{ translate('file') }}
                                                        </button>
                                                    @endif

                                                </div>
                                            </td>
                                            <td class="text-center">{{$detail['qty'] ?? 0}}</td>
                                            <td class="text-end">{{setCurrencySymbol(amount:  usdToDefaultCurrency(amount:   ($detail['price'] ?? 0)*($detail['qty'] ?? 0)), currencyCode: getCurrencyCode()) }}</td>
                                            <td class="text-end">$35.00</td>
                                            <td class="text-end">{{setCurrencySymbol(amount:  usdToDefaultCurrency(amount:   $detail['discount'] ?? 0), currencyCode: getCurrencyCode()) }}</td>
                                            @php($itemPrice+=(($detail['price'] ?? 0)*($detail['qty'] ?? 0)))
                                            @php($subtotal=(($detail['price'] ?? 0)*($detail['qty'] ?? 0))-($detail['discount'] ?? 0))
                                            @php($productPrice = ($detail['price'] ?? 0)*($detail['qty'] ?? 0))
                                            @php($totalProductPrice+=$productPrice)
                                            <td class="text-end">{{setCurrencySymbol(amount:  usdToDefaultCurrency(amount:  $subtotal), currencyCode: getCurrencyCode()) }}</td>
                                        </tr>
                                        @php($discount+=($detail['discount'] ?? 0))
                                        @php($total+=$subtotal)
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                            @foreach(($order['details'] ?? []) as $key=>$detail)
                                <?php
                                    $productDetails = json_decode($detail['product_details'] ?? null, true);
                                ?>
                                @if(isset($productDetails['product_type']) && ($productDetails['product_type'] ?? '') == 'digital')
                                    <div class="modal fade" id="fileUploadModal-{{ $detail['id'] ??  '' }}"
                                         tabindex="-1" aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <form
                                                    action="{{ route('vendor.orders.digital-file-upload-after-sell') }}"
                                                    method="post" enctype="multipart/form-data" class="form-advance-validation non-ajax-form-validate" novalidate="novalidate">
                                                    @csrf
                                                    <div class="modal-header border-0 px-2 pt-2 pb-0 d-flex justify-content-end">
                                                        <button type="button" class="btn btn-circle border-0 fs-12 text-body bg-section2 shadow-none"
                                                                style="--size: 1.5rem;" data-dismiss="modal" aria-label="Close">
                                                            <i class="fi fi-sr-cross-small fs-16 d-flex"></i>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body pt-0">
                                                        @if(($productDetails['digital_product_type'] ?? '') == 'ready_after_sell')
                                                            <div class="mb-3">
                                                                <h2 class="mb-1 fs-18">
                                                                    {{ translate('File_Upload') }} <span class="text-danger">*</span>
                                                                </h2>
                                                                <p class="fs-12 mb-0">
                                                                    {{ translate('Upload_the_product_file_that_customers_will_receive.') }}
                                                                </p>
                                                            </div>
                                                            <div class="d-flex justify-content-center position-relative lg document-upload-container single_mx-100 h-100">
                                                                <div class="document-file-assets"
                                                                    data-picture-icon="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/picture.svg') }}"
                                                                    data-document-icon="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/document.svg') }}"
                                                                    data-blank-thumbnail="{{ dynamicAsset(path: 'public/assets/back-end/img/file-placeholder.png') }}">
                                                                </div>

                                                                    <?php
                                                                    $fileTypeForDigitalReadyUrl = 'file';
                                                                    if ($detail?->digital_file_after_sell_full_url && isset($detail->digital_file_after_sell_full_url['key'])) {
                                                                        $digitalReadyUrlFileKey = $detail->digital_file_after_sell_full_url['key'] ?? '';
                                                                        $extDigitalReadyUrl = strtolower(pathinfo($digitalReadyUrlFileKey, PATHINFO_EXTENSION));

                                                                        $mapDigitalReadyUrl = [
                                                                            'jpg' => 'image',
                                                                            'jpeg' => 'image',
                                                                            'png' => 'image',
                                                                            'gif' => 'image',
                                                                            'pdf' => 'pdf',
                                                                            'zip' => 'zip',
                                                                        ];
                                                                        $fileTypeForDigitalReadyUrl = $mapDigitalReadyUrl[$extDigitalReadyUrl] ?? 'file';
                                                                    }
                                                                    ?>

                                                                <div class="document-existing-file"
                                                                    data-file-url="{{ $detail?->digital_file_after_sell_full_url && isset($detail?->digital_file_after_sell_full_url['path']) ? $detail?->digital_file_after_sell_full_url['path'] : '' }}"
                                                                    data-file-name="{{ $digitalReadyUrlFileKey ?? '' }}"
                                                                    data-file-type="{{ $fileTypeForDigitalReadyUrl }}">
                                                                </div>

                                                                <div class="position-absolute end-0 top-0 p-2 z-2 after_upload_buttons d-none">
                                                                    <div class="d-flex gap-3 align-items-center">
                                                                        <button type="button" class="btn btn-primary icon-btn doc_edit_btn" style="--size: 26px;">
                                                                            <i class="fi fi-sr-pencil"></i>
                                                                        </button>
                                                                        <a type="button" class="btn btn-success icon-btn doc_download_btn" style="--size: 26px;">
                                                                            <i class="fi fi-sr-download"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="document-upload-wrapper lg mw-100 doc-upload-wrapper">
                                                                    <input type="file" class="document_input z-index-1"
                                                                        name="digital_file_after_sell"
                                                                        data-max-size="{{ getFileUploadMaxSize(type: 'file') }}"
                                                                        data-validation-error-msg="{{ translate('File_size_is_too_large_Maximum_').' '.getFileUploadMaxSize(type: 'file').' '.'MB' }}"
                                                                        accept=".jpg,.jpeg,.png,.gif,.zip,.pdf,.xlsx"/>
                                                                    <div class="textbox">
                                                                        <img class="svg"
                                                                            src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/doc-upload-icon.svg') }}"
                                                                            alt="">
                                                                        <p class="mb-3">
                                                                            {{ translate('Select_a_file_or') }}
                                                                            <span class="fw-semibold">
                                                                            {{ translate('Drag_and_Drop_here') }}
                                                                        </span>
                                                                        </p>
                                                                        <button type="button" class="btn btn-outline-primary">
                                                                            {{ translate('Select_File') }}
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" value="{{ $detail['id'] ?? '' }}"
                                                                   name="order_id">
                                                        @endif

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">{{ translate('close') }}</button>
                                                        @if(($productDetails['digital_product_type'] ?? '') == 'ready_after_sell')
                                                            <button type="submit" class="btn btn--primary">
                                                                {{ translate('upload') }}
                                                            </button>
                                                        @endif
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <hr>
                        @php($orderTotalPriceSummary = \App\Utils\OrderManager::getOrderTotalPriceSummary(order: $order))
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-lg-6">
                                <div class="px-sm-4 overflow-x-auto">
                                    <table class="table table-borderless table-sm mb-0 text-sm-right text-nowrap fs-12 title-color">
                                        <tbody>
                                        <tr>
                                            <td class="text-end text-capitalize">{{ translate('item_price') }}</td>
                                            <td class="text-end title-color">
                                                <span>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['itemPrice'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end text-capitalize">{{ translate('item_discount') }}</td>
                                            <td class="text-end title-color">
                                                - <span>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['itemDiscount'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">{{ translate('extra_discount') }}</td>
                                            <td class="text-end title-color">
                                                <span>- {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['extraDiscount'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end text-capitalize">{{ translate('sub_total') }}</td>
                                            <td class="text-end title-color">
                                                <span>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['subTotal'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">{{ translate('coupon_discount') }}</td>
                                            <td class="text-end title-color">
                                                <span>- {{ setCurrencySymbol(amount:  usdToDefaultCurrency(amount: $orderTotalPriceSummary['couponDiscount'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                            </td>
                                        </tr>
                                        @if(($orderTotalPriceSummary['referAndEarnDiscount'] ?? 0) > 0)
                                            <tr>
                                                <td class="text-end">{{ translate('referral_discount') }}</td>
                                                <td class="text-end title-color">
                                                    <span>- {{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['referAndEarnDiscount'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td class="text-end text-uppercase">{{ translate('vat') }}/{{ translate('tax') }}</td>
                                            <td class="text-end title-color">
                                                <span>{{ setCurrencySymbol(amount:  usdToDefaultCurrency(amount: $orderTotalPriceSummary['taxTotal'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-end">
                                                <strong>{{ translate('total') }}</strong>
                                                <span class="fs-10 fw-medium">
                                                        {{ (($orderTotalPriceSummary['tax_model'] ?? '') == 'include') ? '('. translate('Tax_: _Inc.').')' : '' }}
                                                    </span>
                                            </td>
                                            <td class="text-end title-color">
                                                <strong>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['totalAmount'] ?? 0), currencyCode: getCurrencyCode()) }}</strong>
                                            </td>
                                        </tr>
                                        @if ((($order['order_type'] ?? '') == 'pos') || (($order['order_type'] ?? '') == 'POS'))
                                            <tr>
                                                <td class="text-end"><span>{{ translate('paid_amount') }}</span></td>
                                                <td class="text-end title-color">
                                                    <span>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount: $orderTotalPriceSummary['paidAmount'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-end"><span>{{ translate('change_amount') }}</span></td>
                                                <td class="text-end title-color">
                                                    <span>{{ setCurrencySymbol(amount: usdToDefaultCurrency(amount:  $orderTotalPriceSummary['changeAmount'] ?? 0), currencyCode: getCurrencyCode()) }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-flex flex-column gap-3">
                <div class="card">

                    @if(! empty($order['customer'] ?? null))
                        <div class="card-body">
                            <div class="d-flex gap-2 align-items-center justify-content-between mb-3">
                                <h4 class="d-flex gap-2 fs-14 fw-bold mb-0">
                                    <img
                                        src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/vendor-information. png')}}"
                                        alt="">
                                    {{translate('customer_information')}}
                                </h4>
                            </div>
                            <div class="media flex-wrap gap-3 gap-sm-4">
                                <div class="">
                                    <img class="avatar rounded-circle avatar-70 object-fit-cover"
                                         src="{{ getStorageImages(path: ($order['customer']['image_full_url'] ?? null) , type: 'backend-basic') }}"
                                         alt="{{translate('Image')}}">
                                </div>
                                <div class="media-body d-flex flex-column gap-1">
                                    <span class="text-dark">
                                        <span class="fw-semibold">{{ ($order['customer']['f_name'] ?? '') . ' ' . ($order['customer']['l_name'] ?? '') }} </span>
                                    </span>

                                    @if((($order['customer']['email'] ?? '') !== 'walking@customer.com'))
                                        <span class="text-dark fs-12"> <span class="fw-bold">{{ $orderCount ??  0 }}</span> {{translate('orders')}}</span>
                                        <span class="text-dark break-all fs-12">
                                            <span class="fw-semibold">
                                                {{ $order['customer']['phone'] ?? '' }}
                                            </span>
                                        </span>
                                        <span class="text-dark break-all fs-12">{{$order['customer']['email'] ?? ''}}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="media align-items-center">
                                <span>{{ translate('no_customer_found') }}</span>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="card">
                    <div class="card-body">
                        <h4 class="d-flex gap-2 fs-14 fw-bold mb-3">
                            <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/shop-information.png') }}"
                                 alt="">
                            {{ translate('shop_Information') }}
                        </h4>
                        <div class="media d-flex gap-3 align-items-center">
                            @if($order->seller_is == 'admin')
                                <div class="">
                                    <img class="avatar rounded avatar-70 img-fit-contain "
                                         src="{{ getStorageImages(path: getInHouseShopConfig(key: 'image_full_url'), type: 'shop') }}"
                                         alt="">
                                </div>
                                <div class="media-body d-flex flex-column gap-2">
                                    <h5 class="fs-14 mb-0">{{ getInHouseShopConfig(key: 'name') }}</h5>
                                    <span class="text-dark fs-12"><strong>{{ $totalDelivered }}</strong> {{translate('orders_Served')}}</span>
                                </div>
                            @else
                                @if(!empty($order->seller->shop))
                                    <div class="">
                                        <img class="avatar rounded avatar-70 img-fit"
                                             src="{{ getStorageImages(path:$order->seller->shop->image_full_url , type: 'backend-basic') }}"
                                             alt="">
                                    </div>
                                    <div class="media-body d-flex flex-column gap-2">
                                        <h5 class="fs-14 mb-0">{{ $order->seller->shop->name }}</h5>
                                        <span class="text-dark fs-12"><strong>{{ $totalDelivered }}</strong> {{translate('orders_Served')}}</span>
                                        <span
                                            class="text-dark fs-12"> <strong>{{ $order->seller->shop->contact }}</strong></span>
                                        <div class="d-flex align-items-start gap-2">
                                            <img
                                                src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/location.png')}}"
                                                class="mt-1" alt="">
                                            {{ $order->seller->shop->address }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center p-4">
                                        <img class="w-25"
                                             src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/empty-state-icon/shop-not-found.png')}}"
                                             alt="{{translate('image_description')}}">
                                        <p class="mb-0 fs-12">{{ translate('no_shop_found').'!'}}</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/file-upload/pdf.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/file-upload/pdf-worker.min.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/file-upload/multiple-document-upload.js') }}"></script>
    <script src="{{dynamicAsset(path:  'public/assets/back-end/js/vendor/order.js')}}"></script>
@endpush
