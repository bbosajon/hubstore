<div class="modal fade" id="InformationThemeModal" data-bs-backdrop="static"
     data-bs-keyboard="false" tabindex="-1" aria-labelledby="InformationThemeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" id="informationModalContent">
        </div>
    </div>
</div>

@foreach($themes as $key => $theme)
    @if(isset($theme['software_id']))

        @if(theme_root_path() != $key)
        <div class="modal fade" id="shiftThemeModal_{{ $key }}" tabindex="-1"
             aria-labelledby="shiftThemeModalLabel_{{ $key }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div
                        class="modal-header border-0 pb-0 d-flex justify-content-end">
                        <button type="button" class="btn-close border-0 btn-circle bg-section2 shadow-none"
                                data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body px-4 px-sm-5 text-center">
                        <form action="{{ route('admin.system-setup.theme.publish') }}" method="post"
                              class="theme-publish-form">
                            <input type="hidden" value="{{ $key }}" name="theme">
                            <div class="mb-3 text-center">
                                <img width="75" src="{{dynamicAsset(path: 'public/assets/back-end/img/shift.png') }}"
                                     alt="">
                            </div>

                            <h3>{{ translate('do_you_want_to_shift_in_another_theme') }}</h3>
                            <p class="mb-5">
                                {{ translate('if_you_shift_in_another_theme').', '. translate('everything_will_be_rearranged') }}
                                <br class="d-none d-sm-inline">
                                {{ translate('according_to_theme') }}
                            </p>
                            <div class="d-flex justify-content-center gap-3 mb-3">
                                <button type="button" data-bs-dismiss="modal"
                                        class="fs-16 btn btn-secondary px-sm-5">
                                    {{ translate('no') }}
                                </button>
                                <button type="submit" data-bs-dismiss="modal" class="fs-16 btn btn-primary px-sm-5">
                                    {{ translate('yes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if(($key != 'default' && $key != 'theme_aster') && theme_root_path() != $key)
            <div class="modal fade" id="deleteThemeModal_{{ $key }}" tabindex="-1" aria-hidden="true"
                 aria-labelledby="deleteThemeModal_{{ $key }}">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                            <button type="button" class="btn-close border-0 btn-circle bg-section2 shadow-none"
                                    data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body px-4 px-sm-5 text-center">
                                <form action="{{ route('admin.system-setup.theme.delete') }}" method="post"
                                  class="theme-delete-form">
                                @csrf
                                <input type="hidden" value="{{ $key }}" name="theme">
                                <div class="mb-3 text-center">
                                    <img width="75" src="{{ dynamicAsset(path: 'public/assets/back-end/img/delete.png') }}"
                                         alt="">
                                </div>
                                <h3>{{ translate('are_you_sure_you_want_to_delete_the_theme').'?' }}</h3>
                                <p class="mb-5">
                                    {{ translate('once_you_delete').', '.translate('you_will_lost_the_this_theme') }}
                                </p>
                                <div class="d-flex justify-content-center gap-3 mb-3">
                                    <button type="button" class="fs-16 btn btn-secondary px-sm-5" data-bs-dismiss="modal">
                                        {{ translate('cancel') }}
                                    </button>
                                    <button type="submit" class="fs-16 btn btn-danger px-sm-5">
                                        {{ translate('delete') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endforeach
