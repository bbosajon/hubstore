@extends('layouts.admin.app')

@section('title', translate('deal_Of_The_Day'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/deal_of_the_day.png') }}" alt="">
                {{ translate('deal_of_the_day') }}
            </h2>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{route('admin.deal.day') }}"
                              class="text-start form-advance-validation form-advance-inputs-validation form-advance-file-validation non-ajax-form-validate" novalidate="novalidate"
                              method="post">
                            @csrf
                            @php($language = getWebConfig(name:'pnc_language'))
                            @php($defaultLanguage = 'en')
                            @php($defaultLanguage = $language[0])

                            <div class="position-relative nav--tab-wrapper mb-4">
                                <ul class="nav nav-pills nav--tab lang_tab" id="pills-tab" role="tablist">
                                    @foreach($language as $lang)
                                        <li class="nav-item px-0" role="presentation">
                                            <a class="nav-link px-2  text-capitalize {{ $lang == $defaultLanguage? 'active':'' }}" id="{{ $lang }}-link" data-bs-toggle="pill" href="#{{ $lang }}-form" role="tab" aria-selected="true">
                                                {{ ucfirst(getLanguageName($lang)) . '(' . strtoupper($lang) . ')' }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="nav--tab__prev">
                                    <button type="button" class="btn btn-circle border-0 bg-white text-primary">
                                        <i class="fi fi-sr-angle-left"></i>
                                    </button>
                                </div>
                                <div class="nav--tab__next">
                                    <button type="button" class="btn btn-circle border-0 bg-white text-primary">
                                        <i class="fi fi-sr-angle-right"></i>
                                    </button>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="tab-content" id="pills-tabContent">
                                    @foreach($language as $lang)
                                        <div class="tab-pane fade {{ $lang == $defaultLanguage ? 'show active':'' }}" id="{{ $lang }}-form" role="tabpanel" aria-labelledby="{{ $lang }}-form-tab">
                                            <div class="col-md-12">
                                                <label for="name" class="form-label">
                                                    {{ translate('title') }} ({{strtoupper($lang) }})
                                                    @if($lang == $defaultLanguage)<span class="text-danger">*</span>@endif
                                                </label>
                                                <input type="text" name="title[]" class="form-control" id="title"
                                                       data-required-msg="{{ translate('title_is_required') }}"
                                                       placeholder="{{ translate('ex').' '.':'.' '.translate('Daily Spotlight Deal') }}"
                                                    {{ $lang == $defaultLanguage? 'required':'' }}>
                                            </div>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{ $lang }}" id="lang">
                                    @endforeach
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-3">
                                        <label for="name" class="form-label">{{ translate('products') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="product_id" name="product_id" hidden>
                                        <div class="dropdown select-product-search w-100">
                                            <button class="form-select bg-transparent shadow-none text-start dropdown-toggle line-1 word-break select-product-button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" type="button">
                                                {{ translate('select_product') }}
                                            </button>
                                            <div class="dropdown-menu w-100 px-2">
                                                <div class="search-form mb-3">
                                                    <button type="button" class="btn"><i class="fi fi-rr-search"></i>
                                                    </button>
                                                    <input type="text"
                                                           class="js-form-search form-control search-bar-input search-all-type-product"
                                                           placeholder="{{ translate('search menu').'...' }}">
                                                </div>
                                                <div
                                                    class="d-flex flex-column gap-3 max-h-40vh overflow-y-auto overflow-x-hidden search-result-box">
                                                    @include('admin-views.partials._search-product',['products'=>$products])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3 flex-wrap">
                                <button type="reset" id="reset"
                                        class="btn btn-secondary px-5 reset-button">{{ translate('reset') }}</button>
                                <button type="submit" class="btn btn-primary px-5">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-20">
            <div class="col-md-12">
                <div class="card">
                    <div class="px-3 py-4 d-flex justify-content-between align-items-center gap-20 flex-wrap">
                        <h3 class="mb-0 text-capitalize d-flex gap-2">
                            {{ translate('deal_of_the_day') }}
                            <span
                                class="badge text-dark bg-body-secondary fw-semibold rounded-50">{{ $deals->total() }}</span>
                        </h3>
                        <div class="flex-grow-1 max-w-300 min-w-100-mobile">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{ translate('search_by_Title') }}" aria-label="Search orders"
                                               value="{{ request('searchValue') }}" required>
                                        <div class="input-group-append search-submit">
                                            <button type="submit">
                                                <i class="fi fi-rr-search"></i>
                                            </button>
                                        </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless table-thead-bordered align-middle">
                            <thead class="text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('title') }}</th>
                                <th>{{ translate('product_info') }}</th>
                                <th>{{ translate('status') }}</th>
                                <th class="text-center">{{ translate('action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($deals as $k=>$deal)
                                <tr>
                                    <th>{{ $deals->firstItem()+ $k}}</th>
                                    <td class="max-w-250 text-truncate">
                                        <a href="javascript:" target="_blank"
                                           class="fw-semibold text-dark text-hover-primary">{{ $deal['title'] }}
                                        </a>
                                    </td>
                                    <td>{{ isset($deal->product) ? $deal->product->name : translate("not_selected" ) }}</td>
                                    <td>
                                        <form action="{{route('admin.deal.day-status-update') }}" method="post"
                                              id="deal-of-the-day{{ $deal['id'] }}-form" class="no-reload-form reload-true">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $deal['id'] }}">

                                            <label class="switcher" for="deal-of-the-day{{ $deal['id'] }}">
                                                <input
                                                    class="switcher_input custom-modal-plugin"
                                                    type="checkbox" value="1" name="status"
                                                    id="deal-of-the-day{{ $deal['id'] }}"
                                                    {{ $deal['status'] == 1 ? 'checked':'' }}
                                                    data-modal-type="input-change-form"
                                                    data-modal-form="#deal-of-the-day{{ $deal['id'] }}-form"
                                                    data-on-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/deal-of-the-day-status-on.png') }}"
                                                    data-off-image="{{ dynamicAsset(path: 'public/assets/new/back-end/img/modal/deal-of-the-day-status-off.png') }}"
                                                    data-on-title = "{{ translate('want_to_Turn_ON_Deal_of_the_Day_Status').'?' }}"
                                                    data-off-title = "{{ translate('want_to_Turn_OFF_Deal_of_the_Day_Status').'?' }}"
                                                    data-on-message = "<p>{{ translate('if_enabled_this_deal_of_the_day_will_be_available_on_the_website_and_customer_app') }}</p>"
                                                    data-off-message = "<p>{{ translate('if_disabled_this_deal_of_the_day_will_be_hidden_from_the_website_and_customer_app') }}</p>"
                                                    data-on-button-text="{{ translate('turn_on') }}"
                                                    data-off-button-text="{{ translate('turn_off') }}">
                                                <span class="switcher_control"></span>
                                            </label>
                                        </form>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-3">
                                            <a title="{{ trans ('edit') }}"
                                               href="{{route('admin.deal.day-update',[$deal['id']]) }}"
                                               class="btn btn-outline-primary icon-btn edit">
                                                <i class="fi fi-sr-pencil"></i>
                                            </a>
                                            <a title="{{ trans ('delete') }}"
                                               class="btn btn-outline-danger icon-btn delete-data-without-form"
                                               data-action="{{route('admin.deal.day-delete') }}"
                                               data-id="{{ $deal['id'] }}">
                                                <i class="fi fi-rr-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-4">
                        <div class="px-4 d-flex justify-content-lg-end">
                            {{ $deals->links() }}
                        </div>
                    </div>
                    @if(count($deals)==0)
                        @include('layouts.admin.partials._empty-state',['text'=>'no_data_found'],['image'=>'default'])
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/search-product.js') }}"></script>
    <script src="{{ dynamicAsset(path: 'public/assets/back-end/js/admin/deal.js') }}"></script>
@endpush
