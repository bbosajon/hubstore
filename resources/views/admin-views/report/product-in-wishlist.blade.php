@extends('layouts.admin.app')
@section('title', translate('wish_listed_products'))
@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 text-capitalize d-flex gap-2 align-items-center">
                <img width="20" src="{{ dynamicAsset(path: 'public/assets/back-end/img/seller_sale.png')}}" alt="">
                {{translate('product_Report')}}
            </h2>
        </div>

        @include('admin-views.report.product-report-inline-menu')

        <div class="card mb-3">
            <div class="card-body">
                <form action="" id="form-data" method="GET">
                    <h3 class="mb-3">{{translate('filter_Data')}}</h3>
                    <div class="row g-3 align-items-end">
                        <div class="col-sm-6 col-md-4">
                            <select class="custom-select text-ellipsis" name="seller_id">
                                <option value="all" {{ $seller_id == 'all' ? 'selected' : '' }}>{{translate('all')}}</option>
                                <option value="in_house" {{ $seller_id == 'in_house' ? 'selected' : '' }}>{{translate('in-House')}}</option>
                                @foreach($sellers as $seller)
                                    <option value="{{ $seller['id'] }}" {{ $seller_id == $seller['id'] ? 'selected' : '' }}>
                                        {{ $seller?->shop?->name ?? translate('shop_not_found') }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="select-wrapper">
                                <select class="form-select" name="sort">
                                    <option value="DESC" {{ $sort == 'DESC' ? 'selected' : '' }}>{{translate('wishlist_sort_by_(high_to_low)')}}</option>
                                    <option value="ASC" {{ $sort == 'ASC' ? 'selected' : '' }}>{{translate('wishlist_sort_by_(low_to_high)')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 text-right text-md-left">
                            <button type="submit" class="btn btn-primary btn-block">
                                {{translate('filter')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between mb-4">
                    <h3 class="mb-0">
                        {{translate('total_Products')}}
                        <span class="badge badge-info text-bg-info">{{ $products->total() }}</span>
                    </h3>

                    <div class="d-flex gap-3 flex-wrap">
                        <form action="" method="GET" class="mb-0">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="hidden" value="{{ $seller_id }}" name="seller_id">
                                    <input type="hidden" value="{{ $sort }}" name="sort">
                                    <input id="datatableSearch_" type="search" name="search"  class="form-control min-w-300" placeholder="{{translate('search_Product_Name')}}" value="{{ $search }}">
                                    <div class="input-group-append search-submit">
                                        <button type="submit">
                                            <i class="fi fi-rr-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <a type="button" class="btn btn-outline-primary" href="{{route('admin.stock.wishlist-product-export', ['seller_id'=>$seller_id, 'sort'=>$sort, 'search'=>$search])}}">
                            <i class="fi fi-sr-inbox-in"></i>
                            <span class="fs-12">{{ translate('export') }}</span>
                        </a>
                    </div>
                </div>

                <div class="table-responsive" id="products-table">
                    <table
                        class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                        <tr>
                            <th>{{translate('SL')}}</th>
                            <th>
                                {{translate('product_Name')}}
                            </th>
                            <th>
                                {{translate('added_date')}}
                            </th>
                            <th class="text-center">
                                {{translate('total_in_Wishlist')}}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $key=>$data)
                            <tr>
                                <td>{{$products->firstItem()+$key}}</td>
                                <td>
                                    <a class="text-dark text-hover-primary" href="{{route('admin.products.view',['addedBy'=>($data['added_by'] =='seller'?'vendor' : 'in-house'),'id'=>$data['id']])}}">
                                        {{\Illuminate\Support\Str::limit($data['name'], 20)}}
                                    </a>
                                </td>
                                <td>{{ date('d M Y', $data['created_at'] ? strtotime($data['created_at']) : null) }}</td>
                                <td class="text-center">{{ $data->wish_list_count }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive mt-4">
                    <div class="px-4 d-flex justify-content-center justify-content-md-end">
                        {!! $products->links() !!}
                    </div>
                </div>
                @if(count($products)==0)
                    @include('layouts.admin.partials._empty-state',['text'=>'no_product_found'],['image'=>'default'])
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/new/back-end/js/admin/product-report.js') }}"></script>
@endpush

