@extends('theme-views.layouts.app')

@section('title', translate('track_Order').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
        <div class="container">
            <div class="card h-100">
                <div class="card-body py-xxl-5 py-lg-4 px-sm-4">
                    <h2 class="mb-2 text-center">{{ translate('track_order') }}</h2>
                    <form action="{{route('track-order.result')}}" type="submit" method="get" class="p-sm-3">
                        @csrf
                        <div class="section-bg-cmn rounded-10 p-xl-5 p-4">
                            <div class="row g-3 align-items-end">
                                <div class="col-sm-6 col-md-6 col-lg-5">
                                    <div class="flex-grow-1 d-flex gap-3">
                                        <div class="form-group flex-grow-1">
                                            <label for="order_id">{{ translate('order_ID') }}</label>
                                            <input type="text" id="order_id" name="order_id" class="form-control" value="{{ old('order_id') }}" placeholder="{{ translate('order_ID') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-5">
                                    <div class="form-group flex-grow-1">
                                        <label for="phone_or_email">{{ translate('phone') }}</label>
                                        <input type="tel" id="phone_or_email" class="form-control" value="{{ old('phone_number') }}" placeholder="{{ translate('phone') }}" name="phone_number">
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-2">
                                    <button type="submit" class="btn btn-primary w-100 h-45 flex-grow-1">{{ translate('track_order') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="text-center mt-5">
                        <img width="92" src="{{ theme_asset('assets/img/media/track-order.png') }}" class="dark-support mb-2" alt="{{translate('image')}}">
                        <p class="text-muted">{{ translate('enter_your_order_ID_&_phone_to_get_delivery_updates') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
