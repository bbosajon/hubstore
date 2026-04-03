@php use function App\Utils\order_status_history; @endphp
@extends('theme-views.layouts.app')

@section('title', translate('order_details').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="card h-100">
                        <div class="card-body p-lg-4">
                            @include('theme-views.users-profile.account-order-details._order-details-head', ['order'=>$orderDetails])
                            <div class="mt-4 card">
                                <div class="card-body">
                                    <div class="pt-3">
                                        @php
                                                $trackOrderArray = \App\Utils\OrderManager::getTrackOrderStatusHistory(orderId: $orderDetails['id'], isOrderOnlyDigital: $isOrderOnlyDigital);

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
                                            @endphp

                                        <div class="traking-slide-wrap d-flex justify-content-center direction-ltr">
                                                <ul class="traking-slide-nav nav d-flex flex-nowrap text-nowrap">
                                                    @foreach($trackOrderArray['history'] as $statusKey => $statusData)
                                                        @continue(!in_array($statusKey, $statusesToShow))
                                                        @php
                                                            $isTerminalStatus = in_array($statusKey, $terminalStatuses);
                                                        @endphp

                                                        <li class="traking-item {{ $statusData['status'] ? 'active' : '' }} text-center w-240 position-relative z-1">
                                                            <div class="state-img d-center rounded-10 w-40 h-40 section-bg-cmn2 mb-15 mx-auto">
                                                                @if($isTerminalStatus)
                                                                    <i class="bi bi-x-circle-fill fs-20"></i>
                                                                @else
                                                                    <img width="20" class="svg" src="{{ theme_asset('assets/img/icons/' . $statusIcons[$statusKey]) }}" alt="icon">
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
                                                                @if($statusKey === 'order_placed' && $statusData['status'])
                                                                    <p class="fs-12 m-0">{{ translate('Order Placed') }}</p>
                                                                @endif
                                                                @if($statusKey === 'order_is_on_the_way' && $statusData['status'] && !$trackOrderArray['is_digital_order'])
                                                                    <p class="fs-12 mb-0 mt-1">{{ translate('Your deliveryman is coming') }}</p>
                                                                @endif
                                                                @if($isTerminalStatus && $statusData['status'])
                                                                    <a href="javascript:" class="fs-12 text-primary mb-0 mt-1">
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
                                                        <button type="button" class="btn btn-click-prev mr-auto border-0 btn-primary rounded-circle p-2 d-center">
                                                            <i class="bi bi-chevron-left fs-14 lh-1"></i>
                                                        </button>
                                                    </div>
                                                    <div class="button-next align-items-center">
                                                        <button type="button" class="btn btn-click-next ms-auto border-0 btn-primary rounded-circle p-2 d-center">
                                                            <i class="bi bi-chevron-right fs-14 lh-1"></i>
                                                        </button>
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
@endsection

