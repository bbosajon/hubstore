@extends('theme-views.layouts.app')

@section('title', $businessPage?->title.' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-4">
        <div class="container">

            <div class="page-title overlay py-5 __opacity-half background-custom-fit rounded-10 overflow-hidden"
                 data-bg-img="{{ getStorageImages(path: $businessPage?->banner_full_url, type: 'business-page') }}">
                <div class="container">
                    <h1 class="absolute-white text-center text-capitalize">{{ $businessPage?->title }}</h1>
                </div>
            </div>

            <div class="card my-4">
                <div class="card-body p-lg-4 text-dark page-paragraph">
                    {!! $businessPage?->description !!}
                </div>
            </div>
        </div>
    </main>
@endsection
