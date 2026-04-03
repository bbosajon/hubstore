@extends('theme-views.layouts.app')

@section('title', translate('contact_us') . ' | ' . $web_config['company_name'] . ' ' . translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 pt-3 mb-sm-5">
        <div class="container">
            <div class="card">
                <div class="card-body px-lg-5">
                    <h2 class="text-center mb-5 fs-30">{{ translate('get_In') }}<span
                            class="text-primary">{{ translate('touch') }}</span></h2>
                    <div class="row g-4 mb-5 pb-4">
                        <div class="col-md-6 col-lg-4">
                            <div class="media gap-3">
                                <div class="px-3 py-2 bg-primary rounded">
                                    <i class="bi bi-phone-fill fs-4 absolute-white"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-2">{{ translate('call_us') }}</h4>
                                    <a class="fs-18" href="tel:{{ $web_config['phone'] }}">{{ $web_config['phone'] }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="media gap-3">
                                <div class="px-3 py-2 bg-primary rounded">
                                    <i class="bi bi-envelope-fill fs-4 absolute-white"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-2">{{ translate('mail_us') }}</h4>
                                    <a
                                        href="mailto:{{ getWebConfig(name: 'company_email') }}">{{ getWebConfig(name: 'company_email') }}</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="media gap-3">
                                <div class="px-3 py-2 bg-primary rounded">
                                    <i class="bi bi-pin-map-fill fs-4 absolute-white"></i>
                                </div>
                                <div class="media-body">
                                    <h4 class="mb-2">{{ translate('Find_us') }}</h4>
                                    <p>{{ getWebConfig(name: 'shop_address') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row pb-3">
                        <h4 class="col-12 mb-3 order-1">{{ translate('type_here') }}</h4>
                        <div class="col-lg-7 col-xl-8 order-1">
                            <form action="{{ route('contact.store') }}" method="POST" id="contact-form">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label for="name">{{ translate('name') }}</label>
                                            <input type="text" id="name" class="form-control" name="name"
                                                value="{{ old('name') }}" placeholder="{{ translate('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label for="email">{{ translate('email_address') }}</label>
                                            <input type="email" id="email" class="form-control" name="email"
                                                value="{{ old('email') }}"
                                                placeholder="{{ translate('email_address') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label for="message">{{ translate('contact_number') }}</label>
                                            <input type="tel" value="{{ old('mobile_number') }}" name="mobile_number"
                                                class="form-control contact-phone-with-country-picker"
                                                placeholder="{{ translate('contact_number') }}">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group mb-4">
                                            <label for="message">{{ translate('Subject') }}</label>
                                            <input type="text" name="subject" value="{{ old('subject') }}"
                                                class="form-control" placeholder="{{ translate('short_title') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <label for="user_message">{{ translate('message') }}</label>
                                    <textarea name="message" id="user_message" class="form-control" rows="6" required
                                        placeholder="{{ translate('type_your_message_here') . '...' }}"> {{ old('message') }} </textarea>
                                </div>
                                @if (isset($recaptcha) && $recaptcha['status'] == 1)
                                    <div class="dynamic-default-and-recaptcha-section">
                                        <input type="hidden" name="g-recaptcha-response" class="render-grecaptcha-response"
                                            data-action="contact" data-action="contact"
                                            data-input="#login-default-captcha-section"
                                            data-default-captcha="#login-default-captcha-section">

                                        <div class="default-captcha-container d-none" id="login-default-captcha-section"
                                            data-placeholder="{{ translate('enter_captcha_value') }}"
                                            data-base-url="{{ route('g-recaptcha-session-store') }}"
                                            data-session="{{ 'default_captcha_value_contact' }}">
                                        </div>
                                    </div>
                                @else
                                    <div class="default-captcha-container"
                                        data-placeholder="{{ translate('enter_captcha_value') }}"
                                        data-base-url="{{ route('g-recaptcha-session-store') }}"
                                        data-session="{{ 'default_captcha_value_contact' }}">
                                    </div>
                                @endif
                                <div class="d-flex justify-content-end">
                                    <button type="submit" id="contact-form-btn"
                                        class="btn btn-primary rounded px-5">{{ translate('submit') }}</button>
                                </div>
                            </form>
                        </div>
                        <div class="col-lg-5 col-xl-4 mb-4 mb-lg-0 order-0 order-lg-2">
                            <div class="d-flex justify-content-lg-end text-primary">
                                <img width="400" class="dark-support svg"
                                    src="{{ theme_asset('assets/img/media/contact.svg') }}" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
