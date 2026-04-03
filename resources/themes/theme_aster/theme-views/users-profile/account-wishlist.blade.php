@extends('theme-views.layouts.app')

@section('title', translate('my_Wishlists').' | '.$web_config['company_name'].' '.translate('ecommerce'))

@section('content')
    <main class="main-content d-flex flex-column gap-3 py-3 mb-4">
        <div class="container">
            <div class="row g-3">
                @include('theme-views.partials._profile-aside')
                <div class="col-lg-9">
                    <div class="d-flex gap-4 flex-wrap d-lg-none mb-3 mt-2 justify-content-end">
                        @if($wishlists->count() > 0)
                            <a href="javascript:" data-bs-toggle="modal" data-bs-target="#wishlist-modal-delete-all"
                               class="btn-link text-danger text-capitalize">
                                {{ translate('clear_all') }}
                            </a>
                        @endif
                    </div>

                    <div class="modal fade" style="--bs-modal-border-radius: 20px;" id="wishlist-modal-delete-all" tabindex="-1"
                         aria-labelledby="wishlist-modal-delete-all-Label"
                         aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <button type="button" class="btn-close bg-F2F2F2 rounded-circle" style="background-size: 12px;" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-20 py-0 mb-30">
                                    <form action="{{ route('delete-wishlist-all') }}" method="get">
                                        @csrf
                                        <div class="d-flex flex-column align-items-center text-center mb-30">
                                            <img src="{{ theme_asset('assets/img/modal/wishlist-clear.svg') }}" width="70" class="aspect-1 h-70px mb-20" id="custom-modal-1753511225620-image" alt="">
                                            <h4 class="modal-title my-3">
                                                {{ translate('do_you_want_to_clear_your_all_wishlist').' ?' }}
                                            </h4>
                                            <div class="text-center px-4">
                                                {{ translate('are_you_sure_you_want_to_clear_all_the_this_products_permanently_from_your_wishlist').' ?' }}
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-center gap-3 mt-3">
                                            <button type="button" class="btn btn-light h-45px px-5" style="--bs-btn-bg: #f2f2f2;" data-bs-dismiss="modal">
                                                {{ translate('No') }}
                                            </button>
                                            <button type="submit" class="btn btn-primary h-45px px-5" data-bs-dismiss="modal">
                                                {{ translate('Yes') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card h-lg-100">
                        <div class="card-body p-lg-4">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                                <h5>
                                    {{ translate('My_Wish_List') }}
                                    @if($wishlists->count() > 0)
                                        <span>({{ $wishlists->count() }})</span>
                                    @endif
                                </h5>
                                <div class="d-lg-flex gap-4 flex-wrap d-none">
                                    @if($wishlists->count()>0)
                                        <a href="javascript:" data-bs-toggle="modal" data-bs-target="#wishlist-modal-delete-all"
                                           class="btn-link text-danger text-capitalize">
                                            {{ translate('clear_all') }}
                                        </a>
                                    @endif @if($wishlists->count()>0)
                                            <a href="javascript:"
                                               data-action="{{ route('cart.add-all-to-cart') }}"
                                               data-items='@json($wishlists)'
                                               class="btn btn-primary add-all-to-cart">
                                                {{ translate('Add_All_to_Cart') }}
                                            </a>
                                        @endif
                                </div>
                            </div>
                            <div class="mt-4" id="set-wish-list">
                                @include('theme-views.partials._wish-list-data',['wishlists'=>$wishlists, 'brand_setting'=>$brand_setting])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
