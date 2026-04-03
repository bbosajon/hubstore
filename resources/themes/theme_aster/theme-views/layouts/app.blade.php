<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session()->get('direction') }}">
<head>

    <meta name="base-url" content="{{ url('/') }}">
    <meta property="og:site_name" content="{{ $web_config['company_name'] }}" />

    <meta name="google-site-verification" content="{{ getWebConfig('google_search_console_code') }}">
    <meta name="msvalidate.01" content="{{ getWebConfig('bing_webmaster_code') }}">
    <meta name="baidu-site-verification" content="{{ getWebConfig('baidu_webmaster_code') }}">
    <meta name="yandex-verification" content="{{ getWebConfig('yandex_webmaster_code') }}">

    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="robots" content="index, follow">
    <meta name="_token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ $web_config['fav_icon']['path'] }}"/>

    <link rel="stylesheet" href="{{ theme_asset('assets/css/fonts-init.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/css/bootstrap-icons.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/magnific-popup-1.1.0/magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/swiper/swiper-bundle.min.css') }}"/>
    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/sweet_alert/sweetalert2.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/toastr.css') }}"/>

    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/backend/webfonts/uicons-regular-rounded.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/backend/webfonts/uicons-solid-rounded.css') }}">

    <link rel="stylesheet" href="{{ theme_asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ theme_asset('assets/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/backend/libs/intl-tel-input/css/intlTelInput.css') }}">
    <link rel="stylesheet" href="{{ dynamicAsset(path: 'public/assets/backend/libs/google-recaptcha/google-recaptcha-init.css') }}">

    <title>@yield('title')</title>

    @include(VIEW_FILE_NAMES['robots_meta_content_partials'])

    @stack('css_or_js')

    <link rel="stylesheet" href="{{ theme_asset('assets/css/custom.css') }}"/>
    <style>
        :root {
            --bs-primary: {{ $web_config['primary_color'] }};
            --bs-primary-rgb: {{ getHexToRGBColorCode($web_config['primary_color']) }};
            --primary-dark: {{ $web_config['primary_color'] }};
            --primary-light: {{ !empty($web_config['primary_color_light']) ? $web_config['primary_color_light'] : '#FFFFFF' }};
            --bs-secondary: {{ $web_config['secondary_color'] }};
            --bs-secondary-rgb: {{ getHexToRGBColorCode($web_config['secondary_color']) }};
        }

        .announcement-color {
            background-color: {{ $web_config['announcement']['color'] }};
            color: {{$web_config['announcement']['text_color']}};
        }
        .btn-outline-success {
            --bs-btn-hover-bg: {{ $web_config['primary_color'] }} !important;
            --bs-btn-active-bg: {{ $web_config['primary_color'] }} !important;
            --bs-btn-border-color: {{ $web_config['primary_color'] }} !important;
        }
        .btn-outline-success:active {
            background-color: var(--bg-color) !important;
            color: {{ $web_config['primary_color'] }} !important;
            --bs-btn-border-color: {{ $web_config['primary_color'] }} !important;
        }
    </style>

    {!! getSystemDynamicPartials(type: 'analytics_script') !!}
</head>
<body class="toolbar-enabled">
<script>
    'use strict';
    function setThemeMode() {
        if (localStorage.getItem('theme') === null) {
            document.body.setAttribute('theme', 'light');
        } else {
            document.body.setAttribute('theme', localStorage.getItem('theme'));
        }
    }
    setThemeMode();
</script>

<div class="preloader d--none" id="loading">
    <img width="200" alt="" src="{{ getStorageImages(path: getWebConfig(name: 'loader_gif'), type: 'source', source: theme_asset('assets/img/loader.gif')) }}">
</div>
@include('theme-views.layouts.partials._alert-message')
@include('theme-views.layouts.partials._header')
@include('theme-views.layouts.partials._settings-sidebar')
@yield('content')
@include('theme-views.layouts.partials._feature')
@include('theme-views.layouts.partials._footer')

<a href="#" class="back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<div class="app-bar d-xl-none" id="mobile_app_bar">
    @include('theme-views.layouts.partials._app-bar')
</div>

<span class="customize-text"
      data-textno="{{ translate('no') }}"
      data-textyes="{{ translate('yes') }}"
      data-textnow="{{ translate('now') }}"
      data-textsuccessfullycopied="{{ translate('successfully_copied') }}"
      data-text-no-discount="{{ translate('no_discount') }}"
      data-stock-available="{{ translate('stock_available') }}"
      data-stock-not-available="{{ translate('stock_not_available') }}"
      data-update-this-address="{{ translate('update_this_Address') }}"
      data-password-characters-limit="{{ translate('your_password_must_be_at_least_8_characters') }}"
      data-password-not-match="{{ translate('password_does_not_Match') }}"
      data-textpleaseselectpaymentmethods="{{ translate('please_select_a_payment_Methods') }}"
      data-reviewmessage="{{ translate('you_can_review_after_the_product_is_delivered') }}"
      data-refundmessage="{{ translate('you_can_refund_request_after_the_product_is_delivered') }}"
      data-textshoptemporaryclose="{{ translate('This_shop_is_temporary_closed_or_on_vacation').' '.translate('You_cannot_add_product_to_cart_from_this_shop_for_now') }}"
></span>
<span class="system-default-country-code" data-value="{{ getWebConfig(name: 'country_code') ?? 'us' }}"></span>
<span class="cannot_use_zero" data-text="{{ translate('cannot_Use_0_only') }}"></span>
@php($cookie = $web_config['cookie_setting'] ? json_decode($web_config['cookie_setting']['value'], true):null)
@if($cookie && $cookie['status']==1)
    <section id="cookie-section"></section>
@endif

@if(!auth()->guard('customer')->check())
    @include('theme-views.layouts.partials.modal._register')
    @include('theme-views.layouts.partials.modal._login')
@endif

@include('theme-views.layouts.partials.modal._quick-view')
@include('theme-views.layouts.partials.modal._buy-now')
@include('theme-views.layouts.partials.modal._initial')

@php($whatsapp = getWebConfig(name: 'whatsapp'))
<div class="social-chat-icons">
    @if(isset($whatsapp['status']) && $whatsapp['status'] == 1 )
        <div class="">
            <a href="https://wa.me/{{ $whatsapp['phone'] }}?text=Hello%20there!" target="_blank">
                <img src="{{theme_asset('assets/img/whatsapp.svg')}}" width="35" class="chat-image-shadow"
                     alt="Chat with us on WhatsApp">
            </a>
        </div>
    @endif
</div>
@if(isset($isMobile) && $isMobile)
    <div id="app-banner" class="app-download-popup" style="--bottom: 80px;">
        <div class="d-flex gap-3 align-items-center">
            <button type="button" class="btn p-0 px-1 bg-transparent text-dark border-0 shadow-none app-banner-close">
                <i class="fi fi-sr-cross fs-14"></i>
            </button>
            <img width="44" class="img-fit aspect-1 w-40px flex-shrink-0"
                src="{{ getStorageImages(path: $web_config['mob_logo'], type: 'logo') }}"
                alt="6Valley"
            >
            <div class="flex-grow-1">
                <h5 class="fs-14 mb-1">{{ getWebConfig(name: 'company_name')  }} {{ translate('Mobile_App') }}</h5>
                <div class="fs-11">{{ translate('For_better_experience_download') }}</div>
                <button type="button" id="install-btn" class="btn btn-primary fs-12 fw-semibold px-2 py-1 mt-2">
                    {{ translate('Download') }}
                </button>
            </div>
        </div>
    </div>
@endif
@include('theme-views.layouts.partials._translate-text-for-js')
@include('theme-views.layouts.partials._route-for-js')
@include('theme-views.layouts.main-script')
@include('theme-views.layouts._firebase-script')
<span class="d-none" id="text-validate-translate"
      data-required="{{ translate('this_field_is_required') }}"
      data-file-size-larger="{{ translate('file_size_is_larger') }}"
      data-max-limit-crossed="{{ translate('max_limit_crossed') }}"
      data-something-went-wrong="{{ translate('something_went_wrong!') }}"
      data-passwords-do-not-match="{{ translate('passwords_do_not_match') }}"
      data-valid-email="{{ translate('please_enter_a_valid_email') }}"
      data-password-validation="{{ translate('password_must_be_8+_chars_with_upper,_lower,_number_&_symbol') }}"
      data-file-type-not-allowed="{{ translate('Invalid_file_type_selected') }}"
></span>
<span id="imageUploadMaxSize" data-max-size="{{ getFileUploadMaxSize() }}"></span>
@if(isset($isMobile) && $isMobile)
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const banner = document.getElementById("app-banner");
            if (!banner) return;

            const closeBtn = document.querySelector(".app-banner-close");

            const maxViews = 3;
            const today = new Date().toISOString().split("T")[0];

            let bannerViews = parseInt(localStorage.getItem("app_banner_views") || "0");
            let closedToday = localStorage.getItem("app_banner_closed_date");

            if (closedToday === today) {
                banner.style.display = "none";
                return;
            }
            if (bannerViews >= maxViews) {
                banner.style.display = "none";
                return;
            }
            banner.style.display = "block";
            bannerViews++;
            localStorage.setItem("app_banner_views", bannerViews);


            closeBtn.addEventListener("click", function () {
                banner.style.display = "none";
                localStorage.setItem("app_banner_closed_date", today);
            });

        });



        const banner    = document.getElementById('app-banner');
        const closeBtn  = document.querySelector('.app-banner-close');
        const installBtn = document.getElementById('install-btn');

        const STORE_URL = '{{ $isAndroid
            ? (getWebConfig(name: "app_deep_link")["playstore_redirect_url"] ?? "")
            : (getWebConfig(name: "app_deep_link")["app_store_redirect_url"] ?? "")
        }}';

        setTimeout(() => {
            if (!document.hidden) {
                banner.style.display = 'block';
            }
        }, 3000);

        closeBtn.addEventListener('click', () => {
            banner.style.display = 'none';
        });

        installBtn.addEventListener('click', () => {
            window.location.href = STORE_URL;
        });
    </script>
@endif
{!! Toastr::message() !!}
<script>
    function route_alert(route, message) {
        Swal.fire({
            title: "{{translate('are_you_sure')}}?",
            text: message,
            type: 'warning',
            showCancelButton: true,
            cancelButtonColor: 'default',
            confirmButtonColor: '{{$web_config['primary_color']}}',
            cancelButtonText: '{{translate('no')}}',
            confirmButtonText: '{{translate('yes')}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                location.href = route;
            }
        })
    }
</script>
@stack('script')

</body>
</html>
