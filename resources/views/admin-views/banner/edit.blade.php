@extends('layouts.admin.app')

@section('title', translate('banner'))

@section('content')
    <div class="content container-fluid">

        <div class="d-flex justify-content-between flex-wrap gap-2 align-items-center mb-3">
            <div>
                <h2 class="h1 mb-1 text-capitalize d-flex align-items-center gap-2">
                    <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/banner.png') }}" alt="">
                    {{ translate('banner_update_form') }}
                </h2>
            </div>
            <div>
                <a class="btn btn-primary text-white" href="{{ route('admin.banner.list') }}">
                    <i class="fi fi-sr-angle-left"></i> {{ translate('back') }}
                </a>
            </div>
        </div>

        <div class="row text-start">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.banner.update', [$banner['id']]) }}" method="post"
                              enctype="multipart/form-data" class="banner_form form-advance-validation  form-advance-file-validation non-ajax-form-validate" novalidate="novalidate">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="h-100">
                                        <div class="form-group">
                                            <input type="hidden" id="id" name="id">
                                        </div>

                                        <div class="form-group">
                                            <label for="name" class="form-label">{{ translate('banner_type') }} <span class="text-danger">*</span></label>
                                            <select class="custom-select" name="banner_type" required
                                                    id="banner_type_select">
                                                @foreach($bannerTypes as $key => $singleBanner)
                                                    <option
                                                        value="{{ $key }}" {{ $banner['banner_type'] == $key ? 'selected':'' }}>
                                                        {{ $singleBanner }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group" id="banner_resource_type" >
                                            <label for="resource_id"
                                                   class="form-label">{{ translate('resource_type') }} <span class="text-danger">*</span></label>
                                            <select class="custom-select action-display-data" name="resource_type" required>
                                                <option
                                                    value="product" {{ $banner['resource_type'] == 'product'?'selected':'' }}>{{ translate('product') }}</option>
                                                <option
                                                    value="category" {{ $banner['resource_type'] == 'category'?'selected':'' }}>{{ translate('category') }}</option>
                                                <option
                                                    value="shop" {{ $banner['resource_type'] == 'shop'?'selected':'' }}>{{ translate('shop') }}</option>
                                                <option value="brand" {{ $banner['resource_type'] == 'brand'?'selected':'' }}>{{ translate('brand') }}</option>
                                                <option value="custom" {{ $banner['resource_type'] == 'custom' ? 'selected':'' }}>{{ translate('custom') }}</option>
                                            </select>
                                        </div>

                                        <div
                                            class="form-group mb-0 {{ $banner['resource_type'] == 'product'?'d--block':'d--none'}}"
                                            id="resource-product">
                                            <label for="product_id" class="form-label">{{ translate('product') }} </label>
                                            <select class="custom-select"
                                                    name="product_id" id="resource-product">
                                                @foreach($products as $product)
                                                    <option
                                                        value="{{ $product['id'] }}" {{ $banner['resource_id']==$product['id']?'selected':''}}>{{ $product['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div
                                            class="form-group mb-0 {{ $banner['resource_type'] == 'category'?'d--block':'d--none' }}"
                                            id="resource-category">
                                            <label for="name" class="form-label">{{ translate('category') }}</label>
                                            <select class="custom-select"
                                                    name="category_id">
                                                @foreach($categories as $category)
                                                    <option
                                                        value="{{ $category['id'] }}" {{ $banner['resource_id']==$category['id']?'selected':''}}>{{ $category['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div
                                            class="form-group mb-0 {{ $banner['resource_type'] == 'shop'?'d--block':'d--none' }}"
                                            id="resource-shop">
                                            <label for="shop_id" class="form-label">{{ translate('shop') }}</label>
                                            <select class="custom-select"
                                                    name="shop_id">
                                                @foreach($shops as $shop)
                                                    <option
                                                        value="{{ $shop['id'] }}" {{ $banner['resource_id']==$shop['id']?'selected':''}}>{{ $shop['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div
                                            class="form-group mb-0 {{ $banner['resource_type'] == 'brand' ? 'd--block' : 'd--none' }}"
                                            id="resource-brand">
                                            <label for="brand_id" class="form-label">{{ translate('brand') }}</label>
                                            <select class="custom-select"
                                                    name="brand_id">
                                                @foreach($brands as $brand)
                                                    <option
                                                        value="{{ $brand['id'] }}" {{ $banner['resource_id']==$brand['id']?'selected':''}}>{{ $brand['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group mb-0 {{ $banner['resource_type'] == 'custom' ? 'd--block' : 'd--none' }}" id="resource-custom-url">
                                            <label for="name" class="form-label">{{ translate('banner_URL') }} <span class="text-danger">*</span></label>
                                            <input type="url" name="url" class="form-control" id="url"
                                                   placeholder="{{ translate('enter_url') }}" value="{{ $banner['url'] }}">
                                        </div>

                                        @if(theme_root_path() == 'theme_fashion')
                                            <div
                                                class="form-group mt-4 input-field-for-main-banner {{ $banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                                <label for="title"
                                                    class="form-label">{{ translate('Title') }}</label>
                                                <input type="text" name="title" class="form-control" id="title"
                                                    placeholder="{{ translate('Enter_banner_title') }}"
                                                    value="{{ $banner['title'] }}">
                                            </div>
                                            <div
                                                class="form-group mb-0 input-field-for-main-banner {{ $banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                                <label for="sub_title" class="form-label">
                                                    {{ translate('Sub_Title') }}
                                                </label>
                                                <input type="text" name="sub_title" class="form-control"
                                                    id="sub_title"
                                                    placeholder="{{ translate('Enter_banner_sub_title') }}"
                                                    value="{{ $banner['sub_title'] }}">
                                            </div>
                                            <div
                                                class="form-group mt-4 input-field-for-main-banner {{ $banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                                <label for="button_text"
                                                       class="form-label">{{ translate('Button_Text') }}</label>
                                                <input type="text" name="button_text" class="form-control" id="button_text"
                                                       placeholder="{{ translate('Enter_button_text') }}"
                                                       value="{{ $banner['button_text'] }}">
                                            </div>
                                            <div
                                                class="form-group mt-4 mb-0 input-field-for-main-banner {{ $banner['banner_type'] !='Main Banner'?'d-none':''}}">
                                                <label for="background_color"
                                                       class="form-label">{{ translate('background_color') }}</label>
                                                <input type="color" name="background_color"
                                                       class="form-control h-80px px-2 py-2"
                                                       id="background_color" value="{{ $banner['background_color'] }}">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 d-flex flex-column justify-content-center">
                                    <div class="d-flex justify-content-center align-items-center bg-section rounded-8 p-20 w-100 h-100">
                                        <div class="d-flex flex-column gap-30 w-100">
                                            <div class="text-center">
                                                <label for="" class="form-label fw-semibold mb-1">
                                                    {{ translate('banner_image') }} <span class="text-danger">*</span>
                                                </label>
                                                <h4 class="mb-0"><span class="text-info-dark" id="theme_ratio"> ( {{ translate('ratio') }} 4:1 )</span></h4>
                                            </div>
                                            <div class="upload-file">
                                                <input type="file"
                                                       name="image"
                                                       id="banner"
                                                       class="upload-file__input single_file_input"
                                                       data-max-size="{{ getFileUploadMaxSize() }}"
                                                       accept="{{ getFileUploadFormats(skip: '.svg') }}"
                                                       data-required-msg="{{ translate('banner_image_is_required') }}"
                                                    {{ empty(getStorageImages(path: $banner['photo_full_url'], type: 'banner')) ? 'required' : '' }}>

                                                <div class="upload-file__wrapper ratio-4-1">
                                                    <div class="upload-file-textbox text-center">
                                                        <img width="34" height="34" class="svg"
                                                             src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/svg/image-upload.svg') }}"
                                                             alt="image upload">
                                                        <h6 class="mt-1 fw-medium lh-base text-center">
                                                            <span class="text-info">
                                                                {{ translate('Click to upload') }}
                                                            </span>
                                                            <br>
                                                            {{ translate('or_drag_and_drop') }}
                                                        </h6>
                                                    </div>
                                                    <img class="upload-file-img" loading="lazy"
                                                         src="{{ getStorageImages(path:$banner['photo_full_url'],type: 'banner' ) }}"
                                                         data-default-src="{{ getStorageImages(path:$banner['photo_full_url'],type: 'banner' ) }}"
                                                         alt="">
                                                </div>
                                                <div class="overlay">
                                                    <div
                                                        class="d-flex gap-10 justify-content-center align-items-center h-100">
                                                        <button type="button" class="btn btn-outline-info icon-btn view_btn">
                                                            <i class="fi fi-sr-eye"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-info icon-btn edit_btn">
                                                            <i class="fi fi-rr-camera"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="fs-12 text-center max-w-360 m-auto">
                                                {{ getFileUploadFormats(skip: '.svg', asBladeMessage: true).' '. translate('Image_size'). ' : '. translate('Max').' '. getFileUploadMaxSize() . 'MB' }}
                                            </p>
                                            <p class="fs-12 text-center max-w-360 m-auto">
                                                {{ translate('banner_Image_ratio_is_not_same_for_all_sections_in_website.') }}
                                                {{ translate('please_review_the_ratio_before_upload') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 d-flex justify-content-end gap-3">
                                    <button type="submit" class="btn btn-primary px-4">
                                        {{ translate('update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/backend/admin/js/promotion/banner.js') }}"></script>
    <script>
        "use strict";
        $(document).on('ready', function () {
            getThemeWiseRatio();
        });
        let elementBannerTypeSelect = $('#banner_type_select');
        elementBannerTypeSelect.on('change', function () {
            getThemeWiseRatio();
        });
        function getThemeWiseRatio() {
            let bannerType = elementBannerTypeSelect.val();
            let theme = '{{ theme_root_path() }}';
            let themeRatio = {!! json_encode(THEME_RATIO) !!};
            let getRatio = themeRatio[theme][bannerType];
            $('#theme_ratio').text(getRatio);
        }
    </script>
@endpush
