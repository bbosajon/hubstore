@php
    use App\Models\Cart;
    use App\Models\CartShipping;
    use App\Models\ShippingType;
    use App\Utils\Helpers;
    use App\Utils\OrderManager;
    use App\Utils\ProductManager;
    use App\Utils\CartManager;
    use function App\Utils\get_shop_name;
    $shippingMethod = getWebConfig(name: 'shipping_method');
    $cart = CartManager::getCartListQuery()->groupBy('cart_group_id');
@endphp
<div class="container">
    <h4 class="text-center mb-3 text-capitalize">{{ translate('cart_list') }}</h4>
    <form action="javascript:">
        <div class="row gy-3">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-center mb-30">
                            <ul class="cart-step-list">
                                <li class="current cursor-pointer get-view-by-onclick"
                                    data-link="{{route('shop-cart')}}">
                                    <span><i class="bi bi-check2"></i></span> {{ translate('cart') }}</li>
                                <li class="cursor-pointer text-capitalize" data-link="{{ route('checkout-details') }}">
                                    <span><i class="bi bi-check2"></i></span> {{ translate('shopping_details') }}</li>
                                <li><span><i class="bi bi-check2"></i></span> {{ translate('payment') }}</li>
                            </ul>
                        </div>
                        @if(count($cart)==0)
                            @php $physical_product = false; @endphp
                        @endif

                        @foreach($cart as $group_key=>$group)
                            @php
                                $physical_product = false;
                                foreach ($group as $row) {
                                    if ($row->product_type == 'physical' && $row->is_checked) {
                                        $physical_product = true;
                                    }
                                }
                            @endphp
                            <div class="cart_information">
                                @foreach($group as $cart_key=>$cartItem)
                                    @if ($shippingMethod=='inhouse_shipping')
                                            <?php
                                            $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                            $shipping_type = isset($admin_shipping) === true ? $admin_shipping->shipping_type : 'order_wise';
                                            ?>
                                    @else
                                            <?php
                                            if ($cartItem->seller_is == 'admin') {
                                                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                                $shipping_type = isset($admin_shipping) === true ? $admin_shipping->shipping_type : 'order_wise';
                                            } else {
                                                $seller_shipping = ShippingType::where('seller_id', $cartItem->seller_id)->first();
                                                $shipping_type = isset($seller_shipping) === true ? $seller_shipping->shipping_type : 'order_wise';
                                            }
                                            ?>
                                    @endif
                                    @if($cart_key==0)
                                        @php
                                            $verify_status = OrderManager::verifyCartListMinimumOrderAmount($request, $group_key);
                                        @endphp

                                        <div class="bg-light py-2 px-2 px-sm-3 mb-3 rounded">
                                            <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap flex-lg-nowrap">
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    @if($cartItem->seller_is == 'admin')
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <input type="checkbox"
                                                                   class="shop-head-check shop-head-check-desktop">
                                                            <a href="{{ route('vendor-shop',['slug' => getInHouseShopConfig(key: 'slug')]) }}">
                                                                <h5 class="fs-14 line-clamp-1">
                                                                    {{ getInHouseShopConfig(key:'name') }}
                                                                </h5>
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="d-flex gap-2 align-items-center">
                                                            <input type="checkbox" class="shop-head-check shop-head-check-desktop">
                                                            <a href="{{ route('vendor-shop', ['slug' => $cartItem->seller->shop['slug']]) }}">
                                                                @if(get_shop_name($cartItem['seller_id']))
                                                                    <h5>{{ get_shop_name($cartItem['seller_id']) }}</h5>
                                                                @else
                                                                    <h5 class="text-danger fs-14">{{ translate('vendor_not_available') }}</h5>
                                                                @endif
                                                            </a>
                                                        </div>
                                                    @endif
                                                    @if ($verify_status['minimum_order_amount'] > $verify_status['amount'])
                                                        <span
                                                            class="ps-2 text-danger pulse-button minimum-order-amount-message"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-placement="right"
                                                            data-bs-custom-class="custom-tooltip"
                                                            data-bs-title="{{ translate('minimum_Order_Amount') }} {{ webCurrencyConverter($verify_status['minimum_order_amount']) }} {{ translate('for') }} @if($cartItem->seller_is=='admin') {{getInHouseShopConfig(key:'name')}} @else {{ get_shop_name($cartItem['seller_id']) }} @endif">
                                                        <i class="bi bi-info-circle"></i>
                                                    </span>
                                                    @endif
                                                </div>
                                                @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                    @php
                                                        $choosen_shipping=CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first()
                                                    @endphp

                                                    @if(isset($choosen_shipping)===false)
                                                        @php $choosen_shipping['shipping_method_id']=0 @endphp
                                                    @endif
                                                    @php
                                                        $shippings=Helpers::getShippingMethods($cartItem['seller_id'],$cartItem['seller_is']);
                                                    @endphp
                                                    @if($physical_product && $shippingMethod=='sellerwise_shipping' && $shipping_type == 'order_wise')
                                                        @if(count($shippings) > 0)
                                                            <div class="border border-primary-light bg-white rounded px-2 px-sm-3 flex-grow-1 max-w-max-content">
                                                                <div class="shiiping-method-btn d-flex gap-2 p-2 flex-wrap text-dark">
                                                                    <div
                                                                        class="flex-middle flex-nowrap fw-bold text-dark gap-2">
                                                                        <i class="bi bi-truck fs-16"></i>
                                                                        {{ translate('Shipping_Method') }}:
                                                                    </div>
                                                                    <div class="dropdown">
                                                                        <button type="button" class="border-0 bg-transparent d-flex gap-2 align-items-center dropdown-toggle text-dark fw-bold p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                                                <?php
                                                                                $shippings_title = translate('choose_shipping_method');
                                                                                foreach ($shippings as $shipping) {
                                                                                    if ($choosen_shipping['shipping_method_id'] == $shipping['id']) {
                                                                                        $shippings_title = ucfirst($shipping['title']) . ' ( ' . $shipping['duration'] . ' ) ' . webCurrencyConverter($shipping['cost']);
                                                                                    }
                                                                                }
                                                                                ?>
                                                                            <span class="flex-grow-1 text-start"> {{ $shippings_title }}</span>
                                                                        </button>
                                                                        <ul class="dropdown-menu dropdown-left-auto bs-dropdown-min-width--8rem">
                                                                            @foreach($shippings as $shipping)
                                                                                <li class="cursor-pointer set-shipping-id" data-id="{{$shipping['id']}}" data-cart-group="{{$cartItem['cart_group_id']}}">
                                                                                    {{$shipping['title'].' ( '.$shipping['duration'].' ) '.webCurrencyConverter($shipping['cost'])}}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="badge badge-soft-danger cursor-pointer border-danger border fs-12" data-bs-toggle="tooltip"
                                                                  data-bs-placement="top"
                                                                  title="{{ translate('No_shipping_options_available_at_this_shop') }}, {{ translate('please_remove_all_items_from_this_shop') }}">
                                                                {{ translate('shipping_Not_Available') }}
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach

                                <div class="table-responsive d-none d-sm-block scrollbar-none">
                                    @php
                                        $physical_product = false;
                                        foreach ($group as $row) {
                                            if ($row->product_type == 'physical' && $row->is_checked) {
                                                $physical_product = true;
                                            }
                                        }
                                    @endphp
                                    <table class="table align-middle table-borderless rounded-thead-table fs-13 text-nowrap">
                                        <thead class="table-light">
                                        <tr>
                                            <th class="border-0">{{ translate('product_details') }}</th>
                                            <th class="border-0 text-center">{{ translate('qty') }}</th>
                                            <th class="border-0 text-center">{{ translate('price') }}</th>
                                            <th class="border-0 text-center">{{ translate('discount') }}</th>
                                            <th class="border-0 text-center">{{ translate('total') }}</th>
                                            @if ( $shipping_type != 'order_wise')
                                                <th class="border-0 text-center">{{ translate('shipping_cost') }} </th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($group as $cart_key=>$cartItem)
                                            @if($cartItem->allProducts)
                                                @php($product = $cartItem->allProducts)
                                                    @else
                                                        @php($product = $cartItem)
                                                            @endif

                                                                <?php
                                                                $getProductCurrentStock = $product->current_stock;
                                                                if (!empty($product->variation)) {
                                                                    foreach (json_decode($product->variation, true) as $productVariantSingle) {
                                                                        if ($productVariantSingle['type'] == $cartItem->variant) {
                                                                            $getProductCurrentStock = $productVariantSingle['qty'];
                                                                        }
                                                                    }
                                                                }
                                                                ?>

                                                                <?php
                                                                $checkProductStatus = $cartItem->allProducts?->status ?? 0;
                                                                if($cartItem->seller_is == 'admin' && (checkVendorAbility(type: 'inhouse', status: 'temporary_close') || checkVendorAbility(type: 'inhouse', status: 'vacation_status'))) {
                                                                    $checkProductStatus = 0;
                                                                } else if ($cartItem->seller_is == 'seller' && (checkVendorAbility(type: 'vendor', status: 'temporary_close', vendor: $cartItem->allProducts->seller->shop) || checkVendorAbility(type: 'vendor', status: 'vacation_status', vendor: $cartItem->allProducts->seller->shop))) {
                                                                    $checkProductStatus = 0;
                                                                }
                                                                ?>

                                                            <tr>
                                                                <td>
                                                                    <div class="d-flex gap-3 align-items-center">
                                                                        <input type="checkbox"
                                                                               class="shop-item-check shop-item-check-desktop"
                                                                               value="{{ $cartItem['id'] }}" {{ $cartItem['is_checked'] ? 'checked' : '' }}>
                                                                        <div class="media align-items-center gap-3">
                                                                            <div style="--size: 70px;" class="avatar avatar-xxl rounded border position-relative overflow-hidden">
                                                                                <img alt="{{ translate('product') }}"
                                                                                     src="{{ getStorageImages(path: $cartItem->product->thumbnail_full_url, type: 'product') }}"
                                                                                     class="dark-support img-fit rounded img-fluid overflow-hidden {{ $cartItem->allProducts ? ($product->status == 0 ?'custom-cart-opacity-50':'') : 'custom-cart-opacity-50' }}">


                                                                                @if($product->product_type == 'physical' && $getProductCurrentStock < $cartItem['quantity'] || $checkProductStatus == 0)
                                                                                    <span class="temporary-closed position-absolute text-center p-2 fs-12">
                                                                        <span class="text-capitalize">
                                                                            {{ translate('not') }}
                                                                            <br>
                                                                            {{ translate('available') }}
                                                                        </span>
                                                                    </span>
                                                                                @endif
                                                                            </div>
                                                                            <div class="media-body d-flex gap-1 flex-column">
                                                                                <h6 class="text-capitalize line-clamp-1 fs-13">
                                                                                    <a href="{{ $checkProductStatus ? route('product', $cartItem['slug']):'javascript:' }}">{{$cartItem['name']}}</a>
                                                                                </h6>

                                                                                @if(!empty($cartItem['variant']))
                                                                                    <div class="text-wrap">
                                                                        <span class="fs-12 text-dark d-block max-w-200px">
                                                                            {{translate('variant')}} : {{ $cartItem['variant'] }}
                                                                        </span>
                                                                                    </div>
                                                                                @endif
                                                                                <div class="fs-12 text-capitalize text-dark">{{ translate('unit_price') }}
                                                                                    : {{ webCurrencyConverter($cartItem['price']) }}</div>

                                                                                @if($product->product_type == 'physical' && $getProductCurrentStock < $cartItem['quantity'] && $checkProductStatus != 0)
                                                                                    <div class="d-flex text-danger fw-bold">
                                                                                        <span>{{ translate('Out_Of_Stock') }}</span>
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="text-center">
                                                                    @if ($checkProductStatus == 1)
                                                                        @php($isProductCountChangeable = $product->product_type == 'digital' || ($product->product_type == 'physical' && $getProductCurrentStock >= $cartItem['quantity']))
                                                                        <div class="quantity quantity--style-two border-primary-light d-inline-flex align-items-center min-h-35px rounded
                                                        {{ $isProductCountChangeable ? 'justify-content-between min-w-90px' : 'justify-content-center aspect-1' }}">
                                                            <span
                                                                class="quantity__minus cart-qty-btn update-cart-quantity-list-cart-data"
                                                                data-min-order="{{ $product->minimum_order_qty }}"
                                                                data-prevent=true
                                                                data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                                @if($isProductCountChangeable)
                                                                    data-action="{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}">
                                                                @else
                                                                    data-action="delete">
                                                                @endif

                                                                @if($getProductCurrentStock < $cartItem['quantity'] || ($cartItem['quantity'] == ($cartItem?->product?->minimum_order_qty ?? 1)))
                                                                    <img width="17" height="17" src="{{ theme_asset(path: 'assets/img/icons/delete.svg') }}" alt="">
                                                                @else
                                                                    <i class="bi bi-dash fs-22"></i>
                                                                @endif

                                                            </span>

                                                                            <input type="text"
                                                                                   class="quantity__qty update-cart-quantity-list-cart-data-input {{ $isProductCountChangeable ? '' : 'd-none' }}"
                                                                                   value="{{ $isProductCountChangeable ? $cartItem['quantity'] : ($cartItem?->product?->minimum_order_qty ?? 1) }}" name="quantity"
                                                                                   id="cartQuantityWeb{{$cartItem['id']}}"
                                                                                   data-min-order="{{ $product->minimum_order_qty }}"
                                                                                   data-cart="{{ $cartItem['id'] }}" data-value="0"
                                                                                   data-action=""
                                                                                   data-current-stock="{{ $getProductCurrentStock }}"
                                                                                   data-min="{{ $cartItem?->product?->minimum_order_qty ?? 1 }}">
                                                                            <span
                                                                                class="quantity__plus cart-qty-btn update-cart-quantity-list-cart-data  {{ $isProductCountChangeable ? '' : 'd-none' }}""
                                                                            data-prevent=true
                                                                            data-min-order="{{ $product->minimum_order_qty }}"
                                                                            data-cart="{{ $cartItem['id'] }}" data-value="1"
                                                                            data-action="">
                                                                            <i class="bi bi-plus fs-22"></i>
                                                                            </span>
                                                                        </div>
                                                                    @else
                                                                        <div class="quantity quantity--style-two border-primary-light d-inline-flex align-items-center justify-content-center aspect-1 min-h-35px rounded">
                                                            <span class="quantity__minus cartQuantity{{$cartItem['id']}} update-cart-quantity-list-cart-data"
                                                                  data-min-order="{{ $product->minimum_order_qty }}"
                                                                  data-prevent=true
                                                                  data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                                  data-action="delete"
                                                                  data-min="{{$cartItem['quantity']}}">
                                                                <img width="17" height="17" src="{{ theme_asset(path: 'assets/img/icons/delete.svg') }}" alt="">
                                                            </span>
                                                                            <input type="hidden"
                                                                                   class="quantity__qty cartQuantity{{ $cartItem['id'] }}"
                                                                                   value="{{$cartItem['quantity']}}" name="quantity[{{ $cartItem['id'] }}]"
                                                                                   id="cartQuantityWeb{{$cartItem['id']}}"
                                                                                   data-min="{{$cartItem['quantity']}}">
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td class="text-center">{{ webCurrencyConverter($cartItem['price']*$cartItem['quantity']) }}</td>
                                                                <td class="text-center">{{ webCurrencyConverter($cartItem['discount']*$cartItem['quantity']) }}</td>
                                                                <td class="text-center">{{ webCurrencyConverter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</td>
                                                                @if ( $shipping_type != 'order_wise')
                                                                    <td class="text-center">
                                                                        {{ webCurrencyConverter($cartItem['shipping_cost']) }}
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            @endforeach
                                        </tbody>
                                    </table>

                                    @php($free_delivery_status = OrderManager::getFreeDeliveryOrderAmountArray($group[0]->cart_group_id))

                                    @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                                        <div class="free-delivery-area bg-light px-3 py-2 rounded mb-3 max-w-100">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="d-flex justify-content-center align-items-center p-2 rounded {{ $free_delivery_status['percentage'] == 100 ? 'bg-success' : 'bg-warning' }} text-absolute-white">

                                                    <img
                                                        src="{{ theme_asset(path: 'assets/img/icons/free-shipping.svg') }}"
                                                        alt="{{ translate('image') }}" class="" width="20" height="20">
                                                </div>
                                                @if ($free_delivery_status['amount_need'] <= 0)
                                                    <span class="text-dark text-capitalize">
                                                        {{ translate('you_got_free_delivery') }}
                                                    </span>
                                                @else
                                                    <div class="d-flex flex-wrap gap-1">
                                                    <span class="need-for-free-delivery fw-bold text-primary">
                                                        {{ webCurrencyConverter($free_delivery_status['amount_need']) }}
                                                    </span>
                                                        <span class="text-dark opacity-75 text-lowercase">
                                                        {{ translate('add_more_for_free_delivery') }}
                                                    </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="progress free-delivery-progress">
                                                <div class="progress-bar {{ $free_delivery_status['percentage'] == 100 ? 'bg-success' : 'bg-warning' }}"
                                                     role="progressbar"
                                                     style="width: {{ $free_delivery_status['percentage'] . '%' }}"
                                                     aria-valuenow="{{ $free_delivery_status['percentage'] }}"
                                                     aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="d-flex flex-column d-sm-none">
                                    @foreach($group as $cart_key=>$cartItem)
                                        @if($cartItem->allProducts)
                                            @php($product = $cartItem->allProducts)
                                        @endif

                                            <?php
                                            $checkProductStatus = $cartItem->allProducts?->status ?? 0;
                                            if($cartItem->seller_is == 'admin' && (checkVendorAbility(type: 'inhouse', status: 'temporary_close') || checkVendorAbility(type: 'inhouse', status: 'vacation_status'))) {
                                                $checkProductStatus = 0;
                                            } else if ($cartItem->seller_is == 'seller' && (checkVendorAbility(type: 'vendor', status: 'temporary_close', vendor: $cartItem->allProducts->seller->shop) || checkVendorAbility(type: 'vendor', status: 'vacation_status', vendor: $cartItem->allProducts->seller->shop))) {
                                                $checkProductStatus = 0;
                                            }
                                            ?>

                                        <div class="border-bottom d-flex align-items-center justify-content-between gap-2 py-2">
                                            <div class="d-flex gap-2 align-items-center">
                                                <input type="checkbox" class="shop-item-check shop-item-check-mobile" value="{{ $cartItem['id'] }}" {{ $cartItem['is_checked'] ? 'checked' : '' }}>
                                                <div class="media align-items-center gap-2">
                                                    <div
                                                        class="avatar avatar-lg rounded border position-relative overflow-hidden">
                                                        <img
                                                            src="{{ getStorageImages(path: $cartItem?->product?->thumbnail_full_url, type: 'product') }}"
                                                            class="dark-support img-fit rounded img-fluid overflow-hidden {{ $checkProductStatus == 0 ? 'custom-cart-opacity-50' : '' }}"
                                                            alt="">
                                                        @if ($checkProductStatus == 0)
                                                            <span class="temporary-closed position-absolute text-center p-2 fs-12">
                                                                <span>{{ translate('N/A') }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="media-body d-flex gap-1 flex-column">
                                                        <h6 class="text-capitalize line-clamp-1 fs-13">
                                                            <a href="{{ $checkProductStatus ? route('product', $cartItem['slug']) : 'javascript:' }}">
                                                                {{ $cartItem['name'] }}
                                                            </a>
                                                        </h6>
                                                        @if(!empty($cartItem['variant']))
                                                            <div>
                                                                <span class="fs-12 text-dark d-block max-w-200px">
                                                                    {{translate('variant')}} : {{ $cartItem['variant'] }}
                                                                </span>
                                                            </div>
                                                        @endif
                                                        <div class="fs-12 text-capitalize text-dark">{{ translate('unit_price') }}
                                                            : {{ webCurrencyConverter($cartItem['price']*$cartItem['quantity']) }}</div>
                                                        <div class="fs-12 text-dark">{{ translate('discount') }}
                                                            : {{ webCurrencyConverter($cartItem['discount']*$cartItem['quantity']) }}</div>
                                                        <div class="fs-12 text-dark">{{ translate('total') }}
                                                            : {{ webCurrencyConverter(($cartItem['price']-$cartItem['discount'])*$cartItem['quantity']) }}</div>
                                                        @if ( $shipping_type != 'order_wise')
                                                            <div class="fs-12 text-dark">{{ translate('shipping_cost') }}
                                                                : {{ webCurrencyConverter($cartItem['shipping_cost']) }}</div>
                                                        @endif

                                                        @if($product->product_type == 'physical' && $getProductCurrentStock < $cartItem['quantity'])
                                                            <div class="d-flex text-danger fw-bold">
                                                                <span>{{ translate('Out_Of_Stock') }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="quantity quantity--style-two flex-column d-inline-flex align-items-center rounded fs-12 {{ $checkProductStatus < 1 ? 'aspect-1' : '' }}">
                                                @if ($checkProductStatus == 1)
                                                    <span class="quantity__minus update-cart-quantity-list-cart-data"
                                                          data-min-order="{{ $product->minimum_order_qty }}"
                                                          data-prevent=true
                                                          data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                          data-action="{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}">

                                                        @if($getProductCurrentStock < $cartItem['quantity'] || ($cartItem['quantity'] == ($cartItem?->product?->minimum_order_qty ?? 1)))
                                                            <img width="17" height="17" src="{{ theme_asset(path: 'assets/img/icons/delete.svg') }}" alt="">
                                                        @else
                                                            <i class="bi bi-dash fs-22"></i>
                                                        @endif
                                                    </span>
                                                    @if($product->product_type == 'physical' && $getProductCurrentStock >= $cartItem['quantity'])
                                                        <input type="text"
                                                               class="quantity__qty update-cart-quantity-list-mobile-cart-data-input"
                                                               value="{{$cartItem['quantity']}}" name="quantity"
                                                               id="cartQuantityMobile{{$cartItem['id']}}"
                                                               data-min-order="{{ $product->minimum_order_qty }}"
                                                               data-cart="{{ $cartItem['id'] }}" data-value="0"
                                                               data-current-stock="{{ $getProductCurrentStock }}"
                                                               data-action="">
                                                        <span class="quantity__plus update-cart-quantity-list-mobile-cart-data"
                                                              data-prevent=true
                                                              data-min-order="{{ $product->minimum_order_qty }}"
                                                              data-cart="{{ $cartItem['id'] }}" data-value="1"
                                                              data-action="">
                                                            <i class="bi bi-plus fs-22"></i>
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="quantity__minus update-cart-quantity-list-mobile-cart-data h-100"
                                                          data-prevent=true
                                                          data-min-order="{{ $product->minimum_order_qty }}"
                                                          data-cart="{{ $cartItem['id'] }}" data-value="-1"
                                                          data-action="{{ $cartItem['quantity'] == $product->minimum_order_qty ? 'delete':'minus' }}">
                                                            <img width="17" height="17" src="{{ theme_asset(path: 'assets/img/icons/delete.svg') }}" alt="">
                                                    </span>
                                                    <input type="hidden"
                                                           class="quantity__qty cartQuantity{{ $cartItem['id'] }}"
                                                           data-min-order="{{ $product->minimum_order_qty ?? 1 }}"
                                                           data-cart="{{ $cartItem['id'] }}" data-value="0" data-action=""
                                                           value="{{$cartItem['quantity']}}" name="quantity"
                                                           id="cartQuantityMobile{{$cartItem['id']}}"
                                                           data-min="{{$cartItem['quantity']}}">
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach

                                    @php($free_delivery_status = OrderManager::getFreeDeliveryOrderAmountArray($group[0]->cart_group_id))

                                    @if ($free_delivery_status['status'] && (session()->missing('coupon_type') || session('coupon_type') !='free_delivery'))
                                        <div class="free-delivery-area bg-light px-3 py-2 rounded mb-3 max-w-100">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="d-flex justify-content-center align-items-center p-2 rounded bg-warning text-absolute-white">
                                                    <img
                                                        src="{{ theme_asset(path: 'assets/img/icons/free-shipping.svg') }}"
                                                        alt="" width="20" height="20" class="">
                                                </div>
                                                @if ($free_delivery_status['amount_need'] <= 0)
                                                    <span class="text-dark text-capitalize">
                                                        {{ translate('you_got_free_delivery') }}
                                                    </span>
                                                @else
                                                    <div class="d-flex flex-wrap gap-1">
                                                        <span class="need-for-free-delivery fw-bold text-primary">
                                                            {{ webCurrencyConverter($free_delivery_status['amount_need']) }}
                                                        </span>
                                                        <span class="text-dark opacity-75 text-lowercase">
                                                            {{ translate('add_more_for_free_delivery') }}
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="progress free-delivery-progress">
                                                <div class="progress-bar" role="progressbar"
                                                     style="width: {{ $free_delivery_status['percentage'] .'%'}}"
                                                     aria-valuenow="{{ $free_delivery_status['percentage'] }}"
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        @if($shippingMethod=='inhouse_shipping')
                                <?php
                                $physical_product = false;
                                foreach ($cart as $group_key => $group) {
                                    foreach ($group as $row) {
                                        if ($row->product_type == 'physical' && $row->is_checked) {
                                            $physical_product = true;
                                        }
                                    }
                                }
                                ?>

                                <?php
                                $admin_shipping = ShippingType::where('seller_id', 0)->first();
                                $shipping_type = isset($admin_shipping) === true ? $admin_shipping->shipping_type : 'order_wise';
                                ?>
                            @if ($shipping_type == 'order_wise' && $physical_product)
                                @php($shippings=Helpers::getShippingMethods(1,'admin'))
                                @php($choosen_shipping=CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())

                                @if(isset($choosen_shipping)===false)
                                    @php($choosen_shipping['shipping_method_id']=0)
                                @endif
                                <div class="row">
                                    <div class="col-12">
                                        <select class="form-control text-dark set-shipping-onchange">
                                            <option>{{ translate('choose_shipping_method')}}</option>
                                            @foreach($shippings as $shipping)
                                                <option
                                                    value="{{$shipping['id']}}" {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                                    {{$shipping['title'].' ( '.$shipping['duration'].' ) '.webCurrencyConverter($shipping['cost'])}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if( $cart->count() == 0)
                            <div class="d-flex justify-content-center align-items-center">
                                <div class="d-flex flex-column justify-content-center align-items-center gap-2 py-5 w-100">
                                    <img width="80" class="mb-3" src="{{ theme_asset('assets/img/empty-state/empty-cart.svg') }}" alt="">
                                    <h5 class="text-center text-muted">
                                        {{ translate('your_cart_is_empty,_and_it_looks_like_you_haven’t_added_anything_yet.') }}
                                    </h5>
                                </div>
                            </div>
                        @endif

                        <form method="get">
                            <div class="form-group mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="order_note"
                                               class="form-label input-label">{{translate('order_note')}} <span
                                                class="input-label-secondary">({{translate('optional')}})</span></label>
                                        <textarea class="form-control w-100" rows="5" id="order_note"
                                                  name="order_note">{{ session('order_note')}}</textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('theme-views.partials._order-summery')
        </div>
    </form>
</div>
@push('script')
    <script src="{{ theme_asset('assets/js/cart.js') }}"></script>
@endpush
