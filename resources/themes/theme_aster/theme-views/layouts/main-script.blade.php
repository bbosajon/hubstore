@php
    use Illuminate\Support\Facades\Cookie;
@endphp

@php($recaptcha = getWebConfig(name: 'recaptcha'))
<span id="get-google-recaptcha-key"
data-value="{{ isset($recaptcha) && $recaptcha['status'] == 1 ? $recaptcha['site_key'] : '' }}"></span>

<script src="{{ theme_asset('assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ theme_asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ dynamicAsset(path: 'public/assets/backend/libs/jquery-validate/jquery.validate.min.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/magnific-popup-1.1.0/jquery.magnific-popup.js') }}"></script>
<script src="{{ theme_asset('assets/plugins/easyzoom/easyzoom.min.js') }}"></script>
<script src="{{ theme_asset('assets/js/toastr.js') }}"></script>
<script src="{{ theme_asset('assets/js/main.js') }}"></script>

<script src="{{ dynamicAsset(path: 'public/assets/backend/libs/intl-tel-input/js/intlTelInput.js') }}"></script>
<script src="{{ dynamicAsset(path: 'public/assets/backend/libs/intl-tel-input/js/utils.js') }}"></script>
<script src="{{ dynamicAsset(path: 'public/assets/backend/libs/intl-tel-input/js/intlTelInout-validation.js') }}"></script>

<script src="{{ dynamicAsset(path: 'public/assets/backend/file-validation/polyfills.js') }}"></script>
<script src="{{ dynamicAsset(path: 'public/assets/backend/file-validation/just-validate.min.js') }}"></script>
<script src="{{ dynamicAsset(path: 'public/assets/backend/file-validation/form-advance-validation.js') }}"></script>

@if (isset($recaptcha) && $recaptcha['status'] == 1)
    <script src="https://www.google.com/recaptcha/api.js?render={{ $recaptcha['site_key'] }}"></script>
@endif
<script src="{{ dynamicAsset(path: 'public/assets/backend/libs/google-recaptcha/google-recaptcha-init.js') }}"></script>

@if(env('APP_MODE') == 'demo')
    <script>
        'use strict'
        function checkDemoResetTime() {
            let currentMinute = new Date().getMinutes();
            if (currentMinute > 55 && currentMinute <= 60) {
                $('#demo-reset-warning').addClass('active');
            } else {
                $('#demo-reset-warning').removeClass('active');
            }
        }
        checkDemoResetTime();
        setInterval(checkDemoResetTime, 60000);
    </script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.traking-slide-wrap').forEach(wrapper => {
        const container = wrapper.querySelector('.nav');
        if (!container) return;

        const btnPrevWrap = wrapper.querySelector('.button-prev');
        const btnNextWrap = wrapper.querySelector('.button-next');
        const item = wrapper.querySelector('.traking-item');

        wrapper.querySelectorAll('.traking-item').forEach(el => {
            el.style.flex = '0 0 auto';
        });
        function updateArrows() {
            const hasOverflow = container.scrollWidth > container.clientWidth;
            if (!hasOverflow) {
                btnPrevWrap?.style.setProperty('display', 'none');
                btnNextWrap?.style.setProperty('display', 'none');
                return;
            }
            const atStart = container.scrollLeft <= 0;
            const atEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 1;
            btnPrevWrap?.style.setProperty('display', atStart ? 'none' : 'flex');
            btnNextWrap?.style.setProperty('display', atEnd ? 'none' : 'flex');
        }
        wrapper.querySelector('.btn-click-prev')?.addEventListener('click', () => {
            const itemWidth = item?.offsetWidth || 0;
            container.scrollBy({ left: -itemWidth, behavior: 'smooth' });
        });
        wrapper.querySelector('.btn-click-next')?.addEventListener('click', () => {
            const itemWidth = item?.offsetWidth || 0;
            container.scrollBy({ left: itemWidth, behavior: 'smooth' });
        });
        container.addEventListener('scroll', updateArrows);
        window.addEventListener('resize', updateArrows);
        new MutationObserver(updateArrows).observe(container, { childList: true, subtree: true });
        new ResizeObserver(updateArrows).observe(container);
        // Initial check
        updateArrows();
    });
});
</script>

<script>
    'use strict';
    $('.delete-action').on('click', function () {
        Swal.fire({
            title: '{{translate("are_you_sure")}}?',
            text: $(this).data('message'),
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{translate('no')}}',
            confirmButtonText: '{{translate('yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = $(this).data('action');
            }
        })
    })
</script>

@if ($errors->any())
    <script>
        'use strict';
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
<script>
    'use strict';
    let cookieSection = $('#cookie-section');
    @php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
    let cookie_content = `
        <div class="cookies active absolute-white py-4">
            <div class="container">
                <h4 class="absolute-white mb-3">{{translate('Your_Privacy_Matter')}}</h4>
                <p>{{ $cookie ? $cookie['cookie_text'] : '' }}</p>
                <div class="d-flex gap-3 justify-content-end mt-4">
                    <button type="button" class="btn absolute-white btn-link" id="cookie-reject">{{translate('no_thanks')}}</button>
                    <button type="button" class="btn btn-primary" id="cookie-accept">{{translate('yes_i_Accept')}}</button>
                </div>
            </div>
        </div>
        `;
    $(document).on('click', '#cookie-accept', function () {
        document.cookie = '6valley_cookie_consent=accepted; max-age=' + 60 * 60 * 24 * 30;
        cookieSection.hide();
    });
    $(document).on('click', '#cookie-reject', function () {
        document.cookie = '6valley_cookie_consent=reject; max-age=' + 60 * 60 * 24;
        cookieSection.hide();
    });

    $(document).ready(function () {
        if (document.cookie.indexOf("6valley_cookie_consent=accepted") !== -1) {
            cookieSection.hide();
        } else if (document.cookie.indexOf("6valley_cookie_consent=reject") !== -1) {
            cookieSection.hide();
        } else {
            cookieSection.html(cookie_content).show();
        }
    });
</script>

@if(!auth('customer')->check())
    <script>
        "use strict";
        $(document).ready(function() {
            const currentUrl = new URL(window.location.href);
            const referral_code_parameter = new URLSearchParams(currentUrl.search).get("referral_code");

            if (referral_code_parameter) {
                $('#registerModal').modal('show');
                let referralCode =  $('#referral_code');
                if (referralCode.length) {
                    referralCode.val(referral_code_parameter);
                }
            }
        });
    </script>
@endif

<script>
    "use strict";
    let errorMessages = {
        valueMissing: $('.please_fill_out_this_field').data('text')
    };

    $('input').each(function () {
        let $el = $(this);
        $el.on('invalid', function (event) {
            let target = event.target,
                validity = target.validity;
            target.setCustomValidity("");
            if (!validity.valid) {
                if (validity.valueMissing) {
                    target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
                }
            }
        });
    });

    $('textarea').each(function () {
        let $el = $(this);
        $el.on('invalid', function (event) {
            let target = event.target,
                validity = target.validity;
            target.setCustomValidity("");
            if (!validity.valid) {
                if (validity.valueMissing) {
                    target.setCustomValidity($el.data('errorRequired') || errorMessages.valueMissing);
                }
            }
        });
    });
</script>
<script src="{{ theme_asset('assets/js/custom.js') }}"></script>
