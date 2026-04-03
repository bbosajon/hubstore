@extends('layouts.vendor.app')

@section('title', translate('review_List'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize">
                <img width="20" src="{{dynamicAsset(path: 'public/assets/back-end/img/product-review.png')}}" class="mb-1 mr-1"
                     alt="">
                {{translate('product_reviews')}}
                        <span class="badge badge-soft-dark radius-50 fs-12">{{ $reviews->total() }}</span>
            </h2>
        </div>
        <div class="card mt-20">
            <div class="p-20 border-bottom">
                <div class="row g-2 align-items-center justify-content-between">
                    <div class="col-sm-6 col-md-7 col-lg-4 col-xxl-3 mb-1 mb-sm-0">
                        <form action="{{ url()->current() }}" method="GET">
                            <div class="input-group input-group-merge input-group-custom">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="tio-search"></i>
                                    </div>
                                </div>
                                <input id="datatableSearch_" type="search" name="searchValue" class="form-control"
                                       placeholder="{{ translate('search_by_Id, Product, Reviewer, Review') }}"
                                       aria-label="Search orders" value="{{ $searchValue }}" >
                                <button type="submit" class="btn btn--primary">{{ translate('search') }}</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-4 col-md-5 col-lg-4">
                       <div class="d-flex align-items-center gap-3 justify-content-end">
                           <div class="position-relative">
                               @if(request('product_id') || request('customer_id') || request('status') || request('from') || request('to'))
                                   <div class="position-absolute inset-inline-end-0 top-0 mt-n1 me-n1 btn-circle bg-danger border border-white border-2 z-2" style="--size: 12px;"></div>
                               @endif
                                   <button type="button"
                                           @if(request('product_id') || request('customer_id') || request('status') || request('from') || request('to'))
                                               class="btn btn--primary px-4"
                                           @else
                                               class="btn btn-outline--primary px-4"
                                           @endif
                                           data-toggle="offcanvas"
                                           data-target="#reviewFilterOffcanvas">
                                       <i class="fi fi-sr-settings-sliders"></i> {{ translate('Filter') }}
                                   </button>
                           </div>
                            <div class="mb-0 form-group">
                                <a type="button" class="btn btn-outline--primary" href="{{ route('vendor.reviews.export', ['product_id' => $product_id, 'customer_id' => $customer_id, 'status' => $status, 'from' => $from, 'to' => $to, 'searchValue'=>$searchValue]) }}">
                                    <i class="fi fi-sr-settings-sliders"></i>
                                    <span class="ps-2">{{ translate('export') }}</span>
                                </a>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
            @php($vendorReviewReplyStatus = getWebConfig('vendor_review_reply_status') ?? 0)
            <div class="table-responsive datatable-custom">
                <table class="table  table-borderless table-thead-bordered table-nowrap table-align-middle card-table text-start">
                    <thead class="thead-light thead-50 text-capitalize">
                    <tr>
                        <th>{{ translate('SL') }}</th>
                        <th>{{ translate('Review_ID') }}</th>
                        <th>{{ translate('product') }}</th>
                        <th>{{ translate('Reviewer') }}</th>
                        <th>{{ translate('rating') }}</th>
                        <th>{{ translate('review') }}</th>
                        <th>{{ translate('Reply') }}</th>
                        <th>{{ translate('date') }}</th>
                        <th class="text-center">{{ translate('status') }}</th>
                        <th class="text-center">{{ translate('action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($reviews as $key => $review)
                        @if ($review->product)
                            <tr>
                                <td>
                                    {{ $reviews->firstItem()+$key }}
                                </td>
                                <td class="text-center">
                                    {{ $review->id }}
                                </td>
                                <td>
                                    <a class="media align-items-center gap-2"
                                       href="{{ route('vendor.products.view', [$review['product_id']]) }}">
                                       <img src="{{ getStorageImages(path: $review->product['thumbnail_full_url'], type: 'backend-product') }}" class="avatar border min-w-45 min-h-45 object-fit-cover" alt="">
                                       <div class="title-color hover-c1 line--limit-2 text-wrap max-w-280 min-w-120">
                                           {{ Str::limit($review->product['name'], 25) }}
                                       </div>
                                    </a>
                                </td>
                                <td>
                                    @if ($review->customer)
                                     <div class="media align-items-center gap-2">
                                        <img src="{{ getStorageImages(path:$review->customer->image_full_url,type: 'backend-profile') }}" class="avatar border rounded-circle min-w-45 min-h-45 object-fit-cover" alt="">
                                        <div class="title-color">
                                            {{ $review->customer->f_name . ' ' . $review->customer->l_name }}
                                        </div>
                                    </div>
                                    @else
                                        <label class="badge badge-soft-danger">{{ translate('customer_removed') }}</label>
                                    @endif
                                </td>
                                <td>
                                    <label class="text-dark mb-0">
                                            <span class="fs-14 d-flex align-items-center gap-1 font-weight-semibold">
                                                <i class="tio-star text-warning"></i>
                                                {{ $review->rating }}
                                            </span>
                                    </label>
                                </td>
                                <td>
                                    <div class="gap-1">
                                        <div
                                            @if($review->comment && strlen($review->comment) > 35)
                                                data-toggle="tooltip"  data-placement="top"  title="{{ $review->comment }}"
                                            @endif
                                        >
                                            {{ $review->comment ? Str::limit($review->comment, 35) : translate('no_comment_found') }}
                                        </div>

                                        <br>
                                        @if($review->attachment_full_url)
                                            <div class="d-flex flex-wrap gap-1 min-w-200">
                                                @foreach ($review->attachment_full_url as $img)
                                                    <a href="{{getStorageImages(path:$img,type: 'backend-basic')}}"
                                                       data-lightbox="mygallery{{ $review['id'] }}">
                                                        <img width="60" height="60"
                                                             class="aspect-1 rounded object-fit-cover"
                                                             src="{{ getStorageImages(path:$img,type: 'backend-basic')}}"
                                                             alt="{{translate('image')}}">
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                 <?php  $replyText = $review?->reply?->reply_text; ?>
                                    <div class="line-2 max-w-250 word-break line--limit-2 text-wrap min-w-120"
                                         @if($replyText && strlen($replyText) > 35)
                                             data-toggle="tooltip" data-placement="top" title="{{ $replyText }}"
                                        @endif>
                                        {{ $replyText ? Str::limit($replyText, 35) : '-' }}
                                    </div>
                                </td>
                                <td>{{ date('d M Y', strtotime($review->created_at)) }}</td>
                                <td>
                                    <form action="{{ route('vendor.reviews.update-status', [$review['id'], $review->status ? 0 : 1]) }}"
                                          method="get" id="reviews-status{{$review['id']}}-form"
                                          class="reviews_status_form">
                                        <label class="switcher mx-auto">
                                            <input type="checkbox" class="switcher_input toggle-switch-message"
                                                   id="reviews-status{{$review['id']}}"
                                                   {{ $review->status ? 'checked' : '' }}
                                                   data-modal-id = "toggle-status-modal"
                                                   data-toggle-id = "reviews-status{{$review['id']}}"
                                                   data-on-image = "customer-reviews-on.png"
                                                   data-off-image = "customer-reviews-off.png"
                                                   data-on-title = "{{translate('Want_to_Turn_ON_Customer_Reviews').'?'}}"
                                                   data-off-title = "{{translate('Want_to_Turn_OFF_Customer_Reviews').'?'}}"
                                                   data-on-message = "<p>{{translate('if_enabled_anyone_can_see_this_review_on_the_user_website_and_customer_app')}}</p>"
                                                   data-off-message = "<p>{{translate('if_disabled_this_review_will_be_hidden_from_the_user_website_and_customer_app')}}</p>">`)">
                                            <span class="switcher_control"></span>
                                        </label>
                                    </form>
                                </td>
                                <td>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <div data-toggle="modal" data-target="#review-view-for-{{ $review['id'] }}">
                                            <a class="btn btn-outline-success btn-sm square-btn" title="{{ translate('View') }}" data-toggle="tooltip">
                                                <i class="tio-invisible"></i>
                                            </a>
                                        </div>
                                        @if($vendorReviewReplyStatus)
                                            <div data-toggle="modal" data-target="#review-update-for-{{ $review['id'] }}">
                                                @if($review?->reply)
                                                    <a class="btn btn-outline-primary btn-sm square-btn" title="{{ translate('Edit') }}" data-toggle="tooltip">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                @else
                                                    <div class="btn btn-outline-warning btn-sm square-btn" title="{{ translate('Review_Reply') }}" data-toggle="tooltip">
                                                        <i class="tio-reply-all"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            @foreach($reviews as $key => $review)
                @if(isset($review->customer))
                    <div class="modal fade" id="review-update-for-{{ $review['id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close text-BFBFBF" data-dismiss="modal" aria-label="Close">
                                        <i class="tio-clear-circle"></i>
                                    </button>
                                </div>
                                <form method="POST" action="{{ route('vendor.reviews.add-review-reply') }}">
                                    @csrf
                                    <div class="modal-body pt-0">
                                        <div class="d-flex flex-wrap align-items-center gap-3 mb-3">
                                            @if(isset($review->product))
                                                <img src="{{ getStorageImages(path:$review?->product?->thumbnail_full_url, type: 'backend-product') }}" width="100" class="rounded aspect-1 border" alt="">
                                                <div class="w-0 flex-grow-1 font-weight-semibold">
                                                    @if($review['order_id'])
                                                        <div class="mb-2">
                                                            {{ translate('Order_ID') }} # {{ $review['order_id'] }}
                                                        </div>
                                                    @endif
                                                    <h4 class="line--limit-2">{{ $review->product['name'] }}</h4>
                                                </div>
                                            @else
                                                <span class="title-color">
                                                    {{ translate('product_not_found') }}
                                                </span>
                                            @endif

                                        </div>
                                        <label class="input-label text--title font-weight-bold">
                                            {{ translate('Review') }}
                                        </label>
                                        <div class="__bg-F3F5F9 p-3 rounded border mb-2">
                                            {{ $review['comment'] }}
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            @if(count($review->attachment_full_url)>0)
                                                @foreach ($review->attachment_full_url as $img)
                                                    <a class="aspect-1 float-left overflow-hidden"
                                                       href="{{ getStorageImages(path:$img,type: 'backend-basic') }}"
                                                       data-lightbox="review-gallery-modal{{ $review['id'] }}" >
                                                        <img width="45" class="rounded aspect-1 border"
                                                             src="{{ getStorageImages(path:$img,type: 'backend-basic') }}"
                                                             alt="{{translate('review_image')}}">
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                        <label class="input-label text--title font-weight-bold pt-4">
                                            {{ translate('Reply') }}
                                        </label>
                                        <input type="hidden" name="review_id" value="{{ $review['id'] }}">
                                        <textarea class="form-control text-area-max-min" rows="3" name="reply_text"
                                                  placeholder="{{ translate('Write_the_reply_of_the_product_review') }}...">{{ $review?->reply?->reply_text ?? '' }}</textarea>
                                        <div class="text-right mt-4">
                                            <button type="submit" class="btn btn--primary">
                                                @if($review?->reply?->reply_text)
                                                    {{ translate('Update') }}
                                                @else
                                                    {{ translate('submit') }}
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
                    @include("vendor-views.reviews._review-modal", ['review' => $review])
            @endforeach
            @include("vendor-views.reviews.partials._filter-offcanvas")

            <div class="table-responsive mt-4">
                <div class="px-4 d-flex justify-content-lg-end">
                    {!! $reviews->links() !!}
                </div>
            </div>
            @if(count($reviews)==0)
                @include('layouts.vendor.partials._empty-state',['text'=>'no_review_found'],['image'=>'default'])
            @endif
        </div>
    </div>
@endsection
@push('script')
    <script src="{{dynamicAsset(path: 'public/assets/back-end/js/search-product.js')}}"></script>
    <script>
        $(document).ready(function () {

            function toggleCustomDate(wrapper) {
                let selectValue = wrapper.find('.date-type-select').val();

                if (selectValue === 'custom') {
                    wrapper.find('.custom-date-div').slideDown(200);
                } else {
                    wrapper.find('.custom-date-div').slideUp(200);
                }
            }

            $(document).on('change', '.date-type-select', function () {
                let wrapper = $(this).closest('.date-filter-wrapper');
                toggleCustomDate(wrapper);
            });

            $('.date-filter-wrapper').each(function () {
                toggleCustomDate($(this));
            });

        });
    </script>
@endpush
