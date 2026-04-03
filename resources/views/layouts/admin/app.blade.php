<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ session('direction') ?? "ltr" }}">

<head>
    <meta charset="utf-8">
    <meta name="_token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="robots" content="nofollow, noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>@yield('title')</title>
    <link rel="shortcut icon"
          href="{{ getStorageImages(path: getWebConfig(name: 'company_fav_icon'), type: 'backend-logo') }}">

    @include("layouts.admin.partials._style-partials")

    {!! ToastMagic::styles() !!}

    @stack('css_or_js')
</head>

<body data-bs-theme="light" class="{{ env('APP_MODE') == 'demo' ? 'demo' : '' }}">
<script type="text/javascript">
    localStorage.getItem('aside-mini') === 'true' ? document.body.classList.add('aside-mini') : document.body.classList.remove('aside-mini');
</script>

<div class="row">
    <div class="col-12 position-fixed loader-container mt-10rem">
        <div id="loading" class="d--none">
            <div id="loader"></div>
        </div>
    </div>
</div>

@include('layouts.admin.partials._header')
@include('layouts.admin.partials._side-bar')

<main id="content" role="main" class="main-content">
    @yield('content')
    @include('layouts.admin.partials._toggle-modal')
    @include('layouts.admin.components.image-modal')
    @include('layouts.admin.partials._sign-out-modal')
    @include('layouts.admin.partials._modals')
    @include('layouts.admin.partials._alert-message')
</main>

<audio id="myAudio">
    <source src="{{ dynamicAsset(path: 'public/assets/backend/sound/notification.mp3') }}" type="audio/mpeg">
</audio>

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

@include('layouts.admin.partials._translator-for-js')
@include("layouts.admin.partials._translated-message-container")
@include("layouts.admin.partials._routes-list-container")
@include("layouts.admin.partials._script-partials")

@stack('script')


</body>

</html>
