<div class="alert--container active">
    <a href="{{ route((is_null(auth('seller')->id()) ? 'admin' : 'vendor').'.messages.index', ['type' => 'customer']) }}">
        <div class="alert alert--message-2 alert-dismissible fade show" id="chatting-new-notification-check"
             role="alert">
            <img width="28" src="{{ dynamicAsset(path: 'public/assets/back-end/img/icons/chatting-notification.svg') }}"
                 alt="">
            <div class="flex-grow-1">
                <h3>{{ translate('Message') }}</h3>
                <span id="chatting-new-notification-check-message">
                    {{ translate('New_Message') }}
                </span>
            </div>
            <button type="button"
                    class="btn p-0 m-0 border-0 fs-18 text-dark text-hover-primary shadow-none position-relative"
                    data-dismiss="alert" aria-label="Close">
                <i class="fi fi-rr-cross-small"></i>
            </button>
        </div>
    </a>

    @if(env('APP_MODE') == 'demo')
        <div class="alert alert--message-2 alert-dismissible fade show" id="demo-reset-warning">
            <img width="28" class="align-self-start"
                 src="{{ dynamicAsset(path: 'public/assets/back-end/img/info-2.png') }}" alt="">
            <div class="flex-grow-1 w-100">
                <h3 class="title text-truncate text-wrap line-2">{{ translate('warning').'!'}}</h3>
                <span class="warning-message">
                    {{ translate('though_it_is_a_demo_site').'.'.translate('_our_system_automatically_reset_after_one_hour_&_that_why_you_logged_out').'.' }}
                </span>
            </div>
            <button type="button"
                    class="btn p-0 m-0 border-0 fs-18 text-dark text-hover-primary shadow-none position-relative"
                    data-dismiss="alert" aria-label="Close">
                <i class="fi fi-rr-cross-small"></i>
            </button>
        </div>
    @endif

    <div class="alert alert--message-2 align-items-start alert-dismissible shadow-sm rounded-10 fade show product-limited-stock-alert">
        <div class="d-flex aspect-1 overflow-hidden">
            <img width="28" class="align-self-start image aspect-1" src="" alt="">
        </div>
        <div class="text-start flex-grow-1 w-100">
            <h3 class="title fs-16 fw-semibold text-truncate text-wrap line-1"></h3>
            <span class="message">
            </span>
            <div class="d-flex justify-content-between gap-3 mt-2">
                <a href="javascript:" class="text-decoration-underline text-primary fw-medium text-capitalize product-stock-alert-hide">
                    {{ translate('do_not_show_again') }}
                </a>
                <a href="javascript:" class="text-decoration-underline text-primary fw-medium text-capitalize product-list">
                    {{ translate('click_to_view') }}
                </a>
            </div>
        </div>
        <button type="button"
                class="btn p-0 m-0 border-0 fs-18 text-dark text-hover-primary shadow-none position-relative product-stock-limit-close">
            <i class="fi fi-rr-cross-small"></i>
        </button>
    </div>

    <div class="alert alert--message-2 align-items-start alert-dismissible ounded-10 shadow-sm fade show product-restock-stock-alert">
        <div class="d-flex min-w-40 aspect-1 overflow-hidden">
            <img width="28" class="align-self-start aspect-1 border rounded image" src="" alt="">
        </div>
        <div class="flex-grow-1 text-start w-100">
            <h3 class="title fs-16 fw-semibold text-truncate text-wrap line-1"></h3>
            <span class="message">
            </span>
            <div class="d-flex justify-content-between gap-3 mt-2">
                <a href="javascript:"
                   class="text-decoration-underline text-primary fw-medium product-restock-request-alert-hide">
                    {{ translate('Don’t show again') }}
                </a>
                <a href="javascript:" class="text-decoration-underline text-primary fw-medium get-view-by-onclick product-link"
                   data-link="">
                    {{ translate('Click_to_view') }}
                </a>
            </div>
        </div>
        <button type="button"
                class="btn p-0 m-0 border-0 fs-18 text-dark text-hover-primary shadow-none position-relative product-restock-stock-close">
            <i class="fi fi-rr-cross-small"></i>
        </button>
    </div>

    <div class="alert alert--message-3 alert--message-for-pos border-bottom alert-dismissible fade show">
        <img width="28" src="{{ dynamicAsset(path: 'public/assets/back-end/img/warning.png') }}" alt="">
        <div class="w-0">
            <h6 class="title text-truncate text-wrap line-2">{{ translate('Warning').'!'}}</h6>
            <span class="warning-message"></span>
        </div>
        <button type="button" class="btn p-0 m-0 border-0 fs-18 text-dark text-hover-primary shadow-none position-relative"
                data-bs-dismiss="alert" aria-label="Close">
            <i class="fi fi-rr-cross-small"></i>
        </button>
    </div>
</div>

