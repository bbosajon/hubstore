@extends('layouts.admin.app')

@section('title', translate('Attribute'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2 align-items-center">
                <img src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/attribute.png') }}" alt="">
                {{ translate('Attribute_Setup') }}
            </h2>
        </div>

        <div class="row product-attribute-management">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <form id="attribute-form" action="{{ route('admin.attribute.store') }}" method="post" class="text-start form-advance-validation form-advance-inputs-validation form-advance-file-validation non-ajax-form-validate" novalidate="novalidate">
                            @csrf
                            <input type="hidden" name="attribute_id" id="attribute-id">
                            <span id="default-language" data-lang="{{ $defaultLanguage }}" style="display: none;"></span>
                            <div class="bg-section rounded-10 p-12 p-sm-20">
                                <div class="table-responsive w-auto overflow-y-hidden mb-3">
                                    <div class="position-relative nav--tab-wrapper">
                                        <ul class="nav nav-underline nav--tab text-capitalize p-0" id="pills-tab"
                                            role="tablist">
                                            @foreach($language as $lang)
                                                <li class="nav-item px-0">
                                                    <a data-bs-toggle="pill" data-bs-target="#{{ $lang }}-form" role="tab"
                                                       class="nav-link px-2 {{ $lang == $defaultLanguage ? 'active' : '' }}"
                                                       id="{{ $lang }}-link">
                                                        {{ getLanguageName($lang).'('.strtoupper($lang).')' }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                        <div class="nav--tab__prev">
                                            <button class="btn btn-circle border-0 bg-white text-primary">
                                                <i class="fi fi-sr-angle-left"></i>
                                            </button>
                                        </div>
                                        <div class="nav--tab__next">
                                            <button class="btn btn-circle border-0 bg-white text-primary">
                                                <i class="fi fi-sr-angle-right"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-content" id="pills-tabContent">
                                    @foreach($language as $lang)
                                        <div class="tab-pane fade {{ $lang == $defaultLanguage ? 'show active' : '' }}"
                                             id="{{ $lang }}-form" aria-labelledby="{{ $lang }}-link" role="tabpanel">
                                            <label class="form-label" for="name-{{ $lang }}">
                                                {{ translate('attribute_Name') }}
                                                @if($lang == $defaultLanguage)<span class="text-danger">*</span>@endif
                                                ({{ strtoupper($lang) }})
                                            </label>
                                            <input type="text" name="name[]" class="form-control attribute-name-input"
                                                   id="name-{{ $lang }}" data-lang="{{ $lang }}"
                                                   data-required-msg="{{ translate('Attribute_name_is_required') }}"
                                                   placeholder="{{ translate('enter_Attribute_Name') }}"
                                                {{$lang == $defaultLanguage? 'required':''}}>
                                        </div>
                                        <input type="hidden" name="lang[]" value="{{$lang }}">
                                    @endforeach
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
                                <button type="button" id="cancel-edit-btn" class="btn btn-secondary min-w-120" style="display: none;">
                                    {{ translate('cancel') }}
                                </button>
                                <button type="reset" id="reset-btn" class="btn btn-secondary min-w-120">{{ translate('reset') }}</button>
                                <button type="submit" id="submit-btn" class="btn btn-primary min-w-120">{{ translate('submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-20">
                        <div class="d-flex justify-content-between align-items-center gap-20 flex-wrap">
                            <h3 class="mb-0 d-flex align-items-center gap-2">
                                {{ translate('Attribute_List') }}
                                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">
                                    {{ $attributes->total() }}
                                </span>
                            </h3>
                            <div class="flex-grow-1 max-w-300 min-w-100-mobile">
                                <form action="{{ url()->current() }}" method="get">
                                    @csrf
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="searchValue"
                                               class="form-control"
                                               placeholder="{{ translate('search_by_Attribute_Name') }}"
                                               aria-label="Search orders" value="{{ request('searchValue') }}" required>
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
                            <table class="table table-hover table-borderless align-middle">
                                <thead class="text-capitalize">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('attribute_Name') }} </th>
                                    <th class="text-center w-200">{{ translate('action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($attributes as $key => $attribute)
                                    <tr>
                                        <td>{{ $attributes->firstItem() + $key }}</td>
                                        <td>{{ $attribute['name'] }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button"
                                                        class="btn btn-outline-info icon-btn attribute-edit-btn"
                                                        title="{{ translate('edit') }}"
                                                        data-id="{{ $attribute['id'] }}"
                                                        data-name="{{ $attribute['name'] }}"
                                                        data-action="{{route('admin.attribute.translation-data', ['id' => $attribute['id']] )}}">
                                                    <i class="fi fi-sr-pencil"></i>
                                                </button>

                                                <a class="btn btn-outline-danger icon-btn attribute-delete-button"
                                                   title="{{ translate('delete') }}"
                                                   id="{{ $attribute['id'] }}">
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
                            <div class="d-flex justify-content-lg-end">
                                {!! $attributes->links() !!}
                            </div>
                        </div>

                        @if(count($attributes) == 0)
                            @include('layouts.admin.partials._empty-state',['text'=>'no_attribute_found'],['image'=>'default'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <span id="route-admin-attribute-delete" data-url="{{ route('admin.attribute.delete') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/backend/admin/js/products/products-management.js') }}"></script>
@endpush
