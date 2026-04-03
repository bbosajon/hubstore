@extends('theme-views.layouts.app')

@section('title', translate('Forgot_Password') . ' | ' . $web_config['company_name'] . ' ' . translate('ecommerce'))
@php($recaptcha = getWebConfig(name: 'recaptcha'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-sm-5">
        <div class="container">
            <div class="card">
                <div class="card-body py-5 px-lg-5">
                    <div class="row align-items-center pb-5">
                        <div class="col-lg-6 mb-5 mb-lg-0">
                            <h2 class="text-center mb-5 text-capitalize">
                                {{ translate('forget_password') }}
                            </h2>
                            <div class="d-flex justify-content-center">
                                <img width="283" class="dark-support" src="{{ theme_asset('assets/img/otp.png') }}"
                                    alt="{{ translate('image') }}">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="d-flex justify-content-center mb-3">
                                <img width="50" class="dark-support" src="{{ theme_asset('assets/img/otp-lock.png') }}"
                                    alt="">
                            </div>
                            <p class="text-muted mx-w mx-auto text-center mb-4 width--18-75rem">
                                {{ translate('we_will_send_you_a_temporary_OTP_in_your_phone') }}
                            </p>
                            <form action="{{ route('customer.auth.forgot-password') }}" class="forget-password-form"
                                id="customer-forgot-password-form" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="recover-email">
                                        {{ translate('Phone') }}
                                    </label>
                                    <input class="form-control clean-phone-input-value" type="text" name="identity"
                                        id="recover-email" autocomplete="off" required
                                        placeholder="{{ translate('enter_your_phone_number') }}">
                                    <span class="fs-12 text-muted">*
                                        {{ translate('must_use_country_code_before_phone_number') }}</span>
                                    <div class="invalid-feedback">
                                        {{ translate('please_provide_valid_identity') . '.' }}
                                    </div>
                                </div>

                                @if ($web_config['firebase_otp_verification'] && $web_config['firebase_otp_verification']['status'])
                                    <div class="generate-firebase-auth-recaptcha" id="firebase-auth-recaptcha-{{ rand(111, 999) }}"></div>
                                @elseif(isset($recaptcha) && $recaptcha['status'] == 1)
                                    <div class="dynamic-default-and-recaptcha-section">
                                        <input type="hidden" name="g-recaptcha-response" class="render-grecaptcha-response" data-action="customer_auth"
                                               data-input="#login-default-captcha-section"
                                               data-default-captcha="#login-default-captcha-section"
                                        >
                                        <div class="default-captcha-container d-none" id="login-default-captcha-section"
                                             data-placeholder="{{ translate('enter_captcha_value') }}"
                                             data-base-url="{{ route('g-recaptcha-session-store') }}"
                                             data-session="{{ 'default_recaptcha_id_customer_auth' }}"
                                        >
                                        </div>
                                    </div>
                                @else
                                    <div class="default-captcha-container"
                                         data-placeholder="{{ translate('enter_captcha_value') }}"
                                         data-base-url="{{ route('g-recaptcha-session-store') }}"
                                         data-session="{{ 'default_recaptcha_id_customer_auth' }}"
                                    >
                                    </div>
                                @endif

                                <div class="d-flex justify-content-center gap-3 mt-2">
                                    <button class="btn btn-outline-primary get-view-by-onclick"
                                        data-link="{{ route('home') }}"
                                        type="button">{{ translate('back_again') }}</button>
                                    <button class="btn btn-primary px-sm-5" type="submit">
                                        {{ translate('verify') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
