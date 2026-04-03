@if(theme_root_path() == 'theme_aster')
    <div class="offcanvas offcanvas-end" tabindex="-1" id="bannerSetupGuide" aria-labelledby="offcanvasSetupGuideLabel"
         data-status="{{ request('offcanvasShow') && request('offcanvasShow') == 'offcanvasSetupGuide' ? 'show' : '' }}" style="--bs-offcanvas-width: 700px;">

        <div class="offcanvas-header bg-body">
            <div>
                <h3 class="mb-1">{{ translate('How_It_Work') }}</h3>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <div class="p-12 p-sm-20 bg-section rounded mb-3 mb-sm-20">
                <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                    <button class="btn-collapse d-flex gap-3 align-items-center bg-transparent border-0 p-0" type="button"
                            data-bs-toggle="collapse" data-bs-target="#bannerCollapse1" aria-expanded="true">
                        <div class="btn-collapse-icon border bg-light icon-btn rounded-circle text-dark">
                            <i class="fi fi-sr-angle-right"></i>
                        </div>
                        <span class="fw-bold text-start">{{ translate('Main_Banner') }} </span>
                    </button>
                </div>

                <div class="collapse mt-3 show" id="bannerCollapse1">
                    <div class="card card-body">
                        <p class="fs-12 mb-0">
                            {{ translate('this_banner_appears_at_the_top_of_your_website_or_app_to_grab_attention_instantly_and_showcase_offers_or_promotions._you_can_add_as_many_banners_as_you_like._when_uploading,_ensure_the_file_size_is_under_2_mb_and_the_image_ratio_is_3:2.') }}
                        </p>
                        <img width="" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/banner-images/main-banner.webp') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="p-12 p-sm-20 bg-section rounded mb-3 mb-sm-20">
                <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                    <button class="btn-collapse d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#bannerCollapse2" aria-expanded="false">
                        <div class="btn-collapse-icon border bg-light icon-btn rounded-circle text-dark collapsed">
                            <i class="fi fi-sr-angle-right"></i>
                        </div>
                        <span class="fw-bold text-start">{{ translate('Popup_banner') }} </span>
                    </button>
                </div>

                <div class="collapse mt-3" id="bannerCollapse2">
                    <div class="card card-body">
                        <p class="fs-12 mb-0">
                            {{ translate('popup_banner_appears_when_user_visit_website._it’s_a_specific_single_type_banner._you_can_active_only_one_banner_at_a_time._when_uploading,_ensure_the_file_size_is_under_2_mb_and_the_image_ratio_is_1:1.') }}
                        </p>
                        <img width="" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/banner-images/popup-banner.webp') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="p-12 p-sm-20 bg-section rounded mb-3 mb-sm-20">
                <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                    <button class="btn-collapse d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#bannerCollapse3" aria-expanded="false">
                        <div class="btn-collapse-icon border bg-light icon-btn rounded-circle text-dark collapsed">
                            <i class="fi fi-sr-angle-right"></i>
                        </div>
                        <span class="fw-bold text-start">{{ translate('Footer_banner') }} </span>
                    </button>
                </div>

                <div class="collapse mt-3" id="bannerCollapse3">
                    <div class="card card-body">
                        <p class="fs-12 mb-0">
                            {{ translate('this_banner_appears_at_the_bottom_of_the_home_page_before_the_footer_section_to_grab_attention_when_any_on_browse_to_footer_section_and_showcase_offers_or_promotions._you_can_add_only_one_banner._when_uploading,_ensure_the_file_size_is_under_2_mb_and_the_image_ratio_is_4:1.') }}
                        </p>
                        <img width="" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/banner-images/footer-banner.webp') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="p-12 p-sm-20 bg-section rounded mb-3 mb-sm-20">
                <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                    <button class="btn-collapse d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#bannerCollapse4" aria-expanded="false">
                        <div class="btn-collapse-icon border bg-light icon-btn rounded-circle text-dark collapsed">
                            <i class="fi fi-sr-angle-right"></i>
                        </div>
                        <span class="fw-bold text-start">{{ translate('Main_section_banner') }} </span>
                    </button>
                </div>

                <div class="collapse mt-3" id="bannerCollapse4">
                    <div class="card card-body">
                        <p class="fs-12 mb-0">
                            {{ translate('main_section_banner_appears_below_at_the_main_banner_of_your_website_grab_attention_instantly_and_showcase_offers_or_promotions._you_can_add_maximum_2_banners_at_a_time._when_uploading,_ensure_the_file_size_is_under_2_mb_and_the_image_ratio_is_2:1.') }}
                        </p>
                        <img width="" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/banner-images/main-section-banner.webp') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="p-12 p-sm-20 bg-section rounded mb-3 mb-sm-20">
                <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                    <button class="btn-collapse d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#bannerCollapse5" aria-expanded="false">
                        <div class="btn-collapse-icon border bg-light icon-btn rounded-circle text-dark collapsed">
                            <i class="fi fi-sr-angle-right"></i>
                        </div>
                        <span class="fw-bold text-start">{{ translate('Header_Banner') }} </span>
                    </button>
                </div>

                <div class="collapse mt-3" id="bannerCollapse5">
                    <div class="card card-body">
                        <p class="fs-12 mb-0">
                            {{ translate('this_banner_appears_at_the_header_nav_bar_section_to_showcase_short_&_effective_offers._when_uploading,_ensure_the_file_size_is_under_2_mb_and_the_image_ratio_is_4:1.') }}
                        </p>
                        <img width="" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/banner-images/header-banner.webp') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="p-12 p-sm-20 bg-section rounded mb-3 mb-sm-20">
                <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                    <button class="btn-collapse d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#bannerCollapse6" aria-expanded="false">
                        <div class="btn-collapse-icon border bg-light icon-btn rounded-circle text-dark collapsed">
                            <i class="fi fi-sr-angle-right"></i>
                        </div>
                        <span class="fw-bold text-start">{{ translate('Sidebar_Banner') }} </span>
                    </button>
                </div>

                <div class="collapse mt-3" id="bannerCollapse6">
                    <div class="card card-body">
                        <p class="fs-12 mb-0">
                            {{ translate('this_banner_appears_at_the_right_side_of_the_intro_section_in_the_website._you_can_add_as_many_banners_as_you_like_but_only_show_one_banner_at_a_time_in_the_website._when_uploading,_ensure_the_file_size_is_under_2_mb_and_the_image_ratio_is_1:2.') }}
                        </p>
                        <img width="" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/banner-images/sidebar-banner.webp') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="p-12 p-sm-20 bg-section rounded mb-3 mb-sm-20">
                <div class="d-flex gap-3 align-items-center justify-content-between overflow-hidden">
                    <button class="btn-collapse d-flex gap-3 align-items-center bg-transparent border-0 p-0 collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#bannerCollapse8" aria-expanded="false">
                        <div class="btn-collapse-icon border bg-light icon-btn rounded-circle text-dark collapsed">
                            <i class="fi fi-sr-angle-right"></i>
                        </div>
                        <span class="fw-bold text-start">{{ translate('Top_Sidebar_Banner') }} </span>
                    </button>
                </div>

                <div class="collapse mt-3" id="bannerCollapse8">
                    <div class="card card-body">
                        <p class="fs-12 mb-0">
                            {{ translate('this_banner_appears_at_the_right_side_of_the_intro_section_in_the_website._you_can_add_as_many_banners_as_you_like_but_only_show_one_banner_at_a_time_in_the_website._when_uploading,_ensure_the_file_size_is_under_2_mb_and_the_image_ratio_is_1:2.') }}
                        </p>

                        <img width="" src="{{ dynamicAsset(path: 'public/assets/new/back-end/img/banner-images/top-sidebar-banner.webp') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif


