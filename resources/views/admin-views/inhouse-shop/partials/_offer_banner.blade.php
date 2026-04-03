<div class="d-flex flex-column gap-20 bg-section p-12 p-sm-20 rounded">
    <div>
        <label for="" class="form-label fw-semibold mb-1">
            {{ translate('offer_banner') }}
        </label>
        <p class="fs-12 mb-0">
            {{ translate('upload_your_shop_offer_banner') }}
        </p>
    </div>

    <div class="upload-file">
        <input type="file" name="offer_banner" class="upload-file__input single_file_input"
               accept=".webp, .jpg, .jpeg, .png, .gif">
        <label class="upload-file__wrapper ratio-4-1">
            <div class="upload-file-textbox text-center">
                <img width="34" height="34" class="svg"
                     src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/svg/image-upload.svg') }}"
                     alt="{{ translate('Image_Upload') }}">
                <h6 class="mt-1 fw-medium lh-base text-center">
                    <span class="text-info">{{ translate('Click_to_upload') }}</span>
                    <br>
                    {{ translate('or_drag_and_drop') }}
                </h6>
            </div>
            <img class="upload-file-img" loading="lazy" src="{{ getStorageImages(path: getInHouseShopConfig(key: 'offer_banner_full_url'), type: 'shop')  }}" data-default-src="" alt="">
        </label>
        <div class="overlay">
            <div class="d-flex gap-10 justify-content-center align-items-center h-100">
                <button type="button" class="btn btn-outline-info icon-btn view_btn">
                    <i class="fi fi-sr-eye"></i>
                </button>
                <button type="button" class="btn btn-outline-info icon-btn edit_btn">
                    <i class="fi fi-rr-camera"></i>
                </button>
            </div>
        </div>
    </div>

    <p class="fs-10 mb-0 text-center">
        {{ "JPG, JPEG, PNG Less Than 1MB" }}
        <span class="fw-medium">
            {{ '(7:1)' }}
        </span>
    </p>
</div>
