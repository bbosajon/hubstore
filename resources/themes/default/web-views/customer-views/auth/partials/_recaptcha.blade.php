@php($recaptcha = getWebConfig(name: 'recaptcha'))

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
