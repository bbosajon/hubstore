<div class="second-el d--none">
     <div class="container">
         <div class="row justify-content-center">
             <div class="col-lg-8">
                 <div class="card">
                     <div class="card-body">
                         <h3 class="mb-4 text-capitalize">{{ translate('create_an_account') }}</h3>
                         <div class="border p-3 p-xl-4 rounded">
                             <h4 class="mb-3 text-capitalize">{{ translate('vendor_information') }}</h4>

                             <div class="row">
                                 <div class="col-sm-6">
                                     <div class="form-group mb-4">
                                         <label class="mb-2 text-capitalize"
                                             for="f_name">{{ translate('first_name') }}
                                             <span class="text-danger">*</span></label>
                                         <input class="form-control" type="text" name="f_name"
                                             placeholder="{{ translate('ex') . ': John' }}" required>
                                     </div>
                                     <div class="form-group mb-4">
                                         <label class="mb-2 text-capitalize" for="l_name">
                                             {{ translate('last_name') }}
                                             <span class="text-danger">*</span>
                                         </label>
                                         <input class="form-control" type="text" name="l_name"
                                             placeholder="{{ translate('ex') . ': Doe' }}" required>
                                     </div>
                                 </div>
                                 <div class="col-sm-6">
                                     <div class="">
                                         <div class="d-flex flex-column gap-3 align-items-center">
                                             <div class="upload-file">
                                                 <input type="file" class="upload-file__input" name="image"
                                                        data-max-size="2"
                                                     accept="{{getFileUploadFormats(skip: '.svg,.webp,.gif')}}" >
                                                 <div class="upload-file__img">
                                                     <div class="temp-img-box">
                                                         <div class="d-flex align-items-center flex-column gap-2">
                                                             <i class="bi bi-upload fs-30"></i>
                                                             <div class="fs-12 text-muted text-capitalize">
                                                                 {{ translate('upload_file') }}
                                                             </div>
                                                         </div>
                                                     </div>
                                                     <img src="#" class="dark-support img-fit-contain border"
                                                         alt="" hidden>
                                                 </div>
                                             </div>

                                             <div class="d-flex flex-column gap-1 upload-img-content text-center">
                                                 <h6 class="text-uppercase mb-1">
                                                     {{ translate('vendor_image') }}
                                                     <span class="text-danger">*</span>
                                                 </h6>
                                                 <div class="text-muted text-capitalize">
                                                     {{ translate('image_ratio') . ' ' . '1:1' }}
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         <div class="border p-3 p-xl-4 rounded mt-4">
                             <h4 class="mb-3 text-capitalize">{{ translate('shop_information') }}</h4>
                             <div class="form-group mb-4">
                                 <label class="mb-2 text-capitalize" for="shop_name">
                                     {{ translate('Shop_Name') }}
                                     <span class="text-danger">*</span>
                                 </label>
                                 <input class="form-control" type="text" name="shop_name"
                                     placeholder="{{ translate('ex') . ': XYZ Shop' }}" required>
                             </div>
                             <div class="form-group mb-4">
                                 <label class="mb-2 text-capitalize" for="shop_address">
                                     {{ translate('Shop_Address') }}
                                     <span class="text-danger">*</span>
                                 </label>
                                 <textarea class="form-control" name="shop_address" rows="4" placeholder="{{ translate('Shop_Address') }}"
                                     required></textarea>
                             </div>

                             <div class="border p-3 p-xl-4 rounded mb-4">
                                 <div class="d-flex flex-column gap-3 align-items-center">
                                     <div class="upload-file">
                                         <input type="file" class="upload-file__input" name="logo"
                                                data-max-size="{{ getFileUploadMaxSize() }}"
                                             accept="{{getFileUploadFormats(skip: '.svg,.gif,.webp')}}">
                                         <div class="upload-file__img">
                                             <div class="temp-img-box">
                                                 <div class="d-flex align-items-center flex-column gap-2">
                                                     <i class="bi bi-upload fs-30"></i>
                                                     <div class="fs-12 text-muted text-capitalize">
                                                         {{ translate('upload_file') }}
                                                     </div>
                                                 </div>
                                             </div>
                                             <img src="#" class="dark-support img-fit-contain border"
                                                 alt="" hidden>
                                         </div>
                                     </div>

                                     <div class="d-flex flex-column gap-1 upload-img-content text-center">
                                         <h6 class="text-uppercase mb-1">
                                             {{ translate('Shop_Image') }}
                                             <span class="text-danger">*</span>
                                         </h6>
                                         <div class="text-muted text-capitalize">
                                             {{ translate('image_ratio') . ' ' . '1:1' }}
                                         </div>
                                         <div class="text-muted text-capitalize">
                                             {{ translate('Image Size : Max 2 MB') }}
                                         </div>
                                     </div>
                                 </div>
                             </div>

                             <div class="border p-3 p-xl-4 rounded mb-4">
                                 <div class="d-flex flex-column gap-3 align-items-center">
                                     <div class="upload-file">
                                         <input type="file" class="upload-file__input" name="banner"
                                                data-max-size="{{ getFileUploadMaxSize() }}"
                                             accept="{{getFileUploadFormats(skip: '.svg,.gif')}}" >
                                         <div class="upload-file__img style--two">
                                             <div class="temp-img-box">
                                                 <div class="d-flex align-items-center flex-column gap-2">
                                                     <i class="bi bi-upload fs-30"></i>
                                                     <div class="fs-12 text-muted text-capitalize">
                                                         {{ translate('upload_file') }}
                                                     </div>
                                                 </div>
                                             </div>
                                             <img src="#" class="dark-support img-fit-contain border"
                                                 alt="" hidden>
                                         </div>
                                     </div>
                                     <div class="d-flex flex-column gap-1 upload-img-content text-center">
                                         <h6 class="text-uppercase mb-1">
                                             {{ translate('Shop_Banner') }}
                                             <span class="text-danger">*</span>
                                         </h6>
                                         <div class="text-muted text-capitalize">
                                             {{ translate('image_ratio') . ' ' . '2:1' }}
                                         </div>
                                         <div class="text-muted text-capitalize">
                                             {{ translate('Image Size : Max 2 MB') }}
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="border p-3 p-xl-4 rounded mb-4">
                                 <div class="d-flex flex-column gap-3 align-items-center">
                                     <div class="upload-file">
                                         <input type="file" class="upload-file__input" name="bottom_banner"
                                                data-max-size="{{ getFileUploadMaxSize() }}"
                                                accept="{{getFileUploadFormats(skip: '.svg,.gif')}}">
                                         <div class="upload-file__img style--two">
                                             <div class="temp-img-box">
                                                 <div class="d-flex align-items-center flex-column gap-2">
                                                     <i class="bi bi-upload fs-30"></i>
                                                     <div class="fs-12 text-muted text-capitalize">
                                                         {{ translate('upload_file') }}
                                                     </div>
                                                 </div>
                                             </div>
                                             <img src="#" class="dark-support img-fit-contain border"
                                                 alt="" hidden>
                                         </div>
                                     </div>
                                     <div class="d-flex flex-column gap-1 upload-img-content text-center">
                                         <h6 class="text-uppercase mb-1">
                                             {{ translate('Shop_Secondary_Banner') }}
                                             <span class="text-danger">*</span>
                                         </h6>
                                         <div class="text-muted text-capitalize">
                                             {{ translate('image_ratio') . ' ' . '2:1' }}
                                         </div>
                                         <div class="text-muted text-capitalize">
                                             {{ translate('Image Size : Max 2 MB') }}
                                         </div>
                                     </div>
                                 </div>
                             </div>
                             <div class="border p-3 p-xl-4 rounded">
                                 <div class="row gy-4">
                                     <div class="col-lg-6">
                                         <div>
                                             <h4 class="mb-3 text-capitalize">{{ translate('Business_TIN') }}</h4>
                                             <div class="form-group mb-4">
                                                 <label class="mb-2 text-capitalize"
                                                     for="">{{ translate('taxpayer_identification_number(TIN)') }}
                                                 </label>
                                                 <input class="form-control" type="text"
                                                     name="tax_identification_number"
                                                     placeholder="{{ translate('type_your_user_name') }}">
                                             </div>
                                             <div class="form-group mb-4">
                                                 <label class="mb-2 text-capitalize" for="">
                                                     {{ translate('Expire_Date') }}
                                                 </label>
                                                 <div class="position-relative">
                                                     <span class="bi bi-calendar icon-absolute-on-right"></span>
                                                     <input type="text"
                                                         class="js-daterangepicker_single-date-with-placeholder form-control"
                                                         placeholder="{{ translate('click_to_add_date') }}"
                                                         name="tin_expire_date" value="" readonly>
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-lg-6">
                                         <div class="form-group mb-0">
                                             <div class="d-flex justify-content-center document-upload-container">
                                                <div class="document-file-assets"
                                                    data-picture-icon="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/picture.svg') }}"
                                                    data-document-icon="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/document.svg') }}"
                                                    data-blank-thumbnail="{{ dynamicAsset(path: 'public/assets/back-end/img/file-placeholder.png') }}">
                                                </div>
                                                 <div class="document-upload-wrapper doc-upload-wrapper">
                                                     <input type="file" name="tin_certificate"
                                                            data-max-size="{{ getFileUploadMaxSize(type: 'file') }}"
                                                            data-validation-error-msg="{{ translate('File_size_is_too_large_Maximum_').' '.getFileUploadMaxSize(type: 'file').' '.'MB' }}"
                                                         class="document_input" accept=".pdf,.doc,.jpg">
                                                     <div class="textbox">
                                                         <img class="svg" alt=""
                                                             src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/doc-upload-icon.svg') }}">
                                                         <p class="fs-12 mb-0">
                                                             {{ translate('Select_a_file_or') }}
                                                             <span class="font-weight-semibold">
                                                                 {{ translate('Drag_and_Drop_here') }}
                                                             </span>
                                                         </p>
                                                     </div>
                                                 </div>
                                             </div>
                                             <div class="d-flex flex-column gap-1 upload-img-content text-center mt-3">
                                                 <h6 class="text-uppercase mb-1">{{ translate('TIN_Certificate') }}
                                                 </h6>
                                                 <div class="text-muted text-capitalize">
                                                     {{ translate('pdf,_doc,_jpg._file_size_:_max_5_MB') }}
                                                 </div>
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                             </div>
                         </div>

                         @php($recaptcha = getWebConfig(name: 'recaptcha'))

                         @if(isset($recaptcha) && $recaptcha['status'] == 1)
                             <div class="dynamic-default-and-recaptcha-section">
                                 <input type="hidden" name="g-recaptcha-response" class="render-grecaptcha-response" data-action="register"
                                        data-input="#login-default-captcha-section"
                                        data-default-captcha="#login-default-captcha-section"
                                 >

                                 <div class="default-captcha-container d-none" id="login-default-captcha-section"
                                      data-placeholder="{{ translate('enter_captcha_value') }}"
                                      data-base-url="{{ route('g-recaptcha-session-store') }}"
                                      data-session="{{ 'vendorRecaptchaSessionKey' }}"
                                 >
                                 </div>
                             </div>
                         @else
                             <div class="default-captcha-container"
                                  data-placeholder="{{ translate('enter_captcha_value') }}"
                                  data-base-url="{{ route('g-recaptcha-session-store') }}"
                                  data-session="{{ 'vendorRecaptchaSessionKey' }}"
                             >
                             </div>
                         @endif

                         <div class="col-12">
                             <label class="custom-checkbox align-items-center">
                                 <input type="checkbox" class="form-check-input" id="terms-checkbox">
                                 <span class="form-check-label">
                                     {{ translate('i_agree_with_the') }}
                                     <a href="{{ route('business-page.view', ['slug' => 'terms-and-conditions']) }}"
                                         target="_blank" class="text-decoration-underline color-bs-primary-force">
                                         {{ translate('terms_&_conditions') }}
                                     </a>
                                 </span>
                             </label>
                         </div>
                         <div class="d-flex justify-content-end mt-4 mb-2 gap-2">
                             <button type="button" class="btn btn-secondary back-to-main-page">
                                 {{ translate('back') }}
                             </button>
                             <button type="submit" class="btn btn-primary disabled" id="vendor-apply-submit">
                                 {{ translate('submit') }}
                             </button>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
