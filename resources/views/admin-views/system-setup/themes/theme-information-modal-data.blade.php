<div class="modal-header border-0 pb-0 d-flex justify-content-end">
    <button type="button" class="btn-close border-0 btn-circle bg-section2 shadow-none reload-by-onclick"
        data-bs-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body px-20 py-0 mb-30 text-center">
    <div class="mb-20 text-center">
        <img width="75" src="{{ dynamicAsset(path: 'public/assets/back-end/img/shift.png') }}" alt="">
    </div>
    <h2 class="mb-3">
        {{ translate('you_have_switched_theme_successfully') }}
        <br>
        {{ translate('from') . ' ' . ucwords(str_replace('_', ' ', $currentTheme)) . ' ' . translate('to') . ' ' . ucwords(str_replace('_', ' ', $themeInfo['name'])) }}
    </h2>
    <p class="mb-0 mx-4">
        {{ translate('please_be_reminded_that,_you_have_to_setup_data_for_these_section_for_'.ucwords(str_replace('_', ' ', $themeInfo['name'])).'.') }}
        {{ translate('otherwise_these_section_data_would_not_function_properly_in_website_and_user_apps.') }}
    </p>


    <div class="bg-section rounded-10 p-12 p-sm-20 mt-30 mb-2">
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <?php
            $mergedArray = array_merge($currentThemeRoutes['route_list'], $themeRoutes['route_list']);
            $new_current_theme_routes = [];
            foreach ($currentThemeRoutes['route_list'] as $data) {
                if (!in_array($data['url'], array_column($themeRoutes['route_list'], 'url'))) {
                    $new_current_theme_routes[] = $data;
                }
            }
            ?>
            @foreach ($new_current_theme_routes as $data)
                @if (in_array($data['url'], array_column($mergedArray, 'url')))
                    <a class="bg-white text-dark border rounded-pill p-3 w-fit-content fw-medium text-nowrap d-flex flex-row gap-2 align-items-center"
                        href="javascript:">
                        {{ translate($data['name']) }}
                        <span class="badge bg-danger rounded-circle theme-features-badge">
                            <i class="fi fi-rr-cross-small"></i></span>
                    </a>
                @endif
            @endforeach

            @foreach ($themeRoutes['route_list'] as $data)
                @if (in_array($data['url'], array_column($mergedArray, 'url')))
                    <a class="bg-white text-dark border rounded-pill p-3 w-fit-content fw-medium text-nowrap d-flex flex-row gap-2 align-items-center"
                        href="{{ $data['url'] }}" target="_blank">
                        {{ translate($data['name']) }}
                        <span class="badge bg-success rounded-circle theme-features-badge">
                            <i class="fi fi-rr-plus-small"></i>
                        </span>
                    </a>
                @endif
            @endforeach
        </div>
    </div>
    <div
        class="bg-warning bg-opacity-10 fs-12 px-12 py-10 text-dark rounded d-flex gap-2 align-items-center mb-30 text-start">
        <i class="fi fi-sr-info text-warning"></i>
        <span>
            {{ translate('please_do_not_forget_to_notify_your_vendors_about_these_changes.') . ' ' . translate('so_that_they_can_also_update_their_store_banners_according_to_the_new_theme_ratio') }}
        </span>
    </div>
    <div class="d-flex flex-column gap-3 justify-content-center align-items-center notify-all-the-sellers-area">
        <button type="button" class="btn btn-primary px-4 w-max-content text-nowrap notify-all-the-sellers">
            {{ translate('Notify_Through_Push_Notification') }}
        </button>
        <div class="text-primary px-4 w-max-content text-nowrap min-w-300 fw-semibold cursor-pointer reload-by-onclick"
            data-bs-dismiss="modal">
            {{ translate('Skip_for_Now') }}
        </div>
    </div>
</div>
