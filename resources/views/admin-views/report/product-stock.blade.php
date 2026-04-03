@extends('layouts.admin.app')
@section('title', translate('product_stock'))
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
                    <div class="row g-3 align-items-center">
                        <div class="col-sm-6 col-md-3">
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
                        <div class="col-sm-6 col-md-3">
                            <select class="custom-select" name="category_id" id="cat_id">
                                <option value="all" {{ $category_id == 'all' ? 'selected' : '' }}>{{translate('all_category')}}</option>
                                @foreach($categories as $category)
                                    <option value="{{$category['id'] }}" {{ $category_id == $category['id'] ? 'selected' : '' }}>{{ $category['default_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="">
                                <div class="select-wrapper">
                                    <select class="form-select" name="sort">
                                        <option value="ASC" {{ $sort == 'ASC' ? 'selected' : '' }}>{{translate('stock_sort_by_(low_to_high)')}}</option>
                                        <option value="DESC" {{ $sort == 'DESC' ? 'selected' : '' }}>{{translate('stock_sort_by_(high_to_low)')}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
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
                        <form action="" method="GET">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="hidden" value="{{ $seller_id }}" name="seller_id">
                                    <input type="hidden" value="{{ $category_id }}" name="category_id">
                                    <input type="hidden" value="{{ $sort }}" name="sort">
                                    <input id="datatableSearch_" type="search" class="form-control min-w-300" name="search" value="{{ $search }}" placeholder="{{translate('search_Product_Name')}}">
                                    <div class="input-group-append search-submit">
                                        <button type="submit">
                                            <i class="fi fi-rr-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <a type="button" class="btn btn-outline-primary" href="{{ route('admin.stock.product-stock-export', ['sort' => request('sort'), 'category_id' => request('category_id'), 'seller_id' => request('seller_id'), 'search' => request('search')]) }}">
                            <i class="fi fi-sr-inbox-in"></i>
                            <span class="fs-12">{{ translate('export') }}</span>
                        </a>
                    </div>
                </div>

                <div class="table-responsive" id="products-table">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table w-100 {{Session::get('direction') === "rtl" ? 'text-right' : 'text-left'}}">
                        <thead class="thead-light thead-50 text-capitalize">
                            <tr>
                                <th>{{translate('SL')}}</th>
                                <th>
                                    {{translate('product_Name')}}
                                </th>
                                <th>
                                    {{translate('last_Updated_Stock')}}
                                </th>
                                <th class="text-center">
                                    {{translate('current_Stock')}}
                                </th>
                                <th class="text-center">
                                    {{translate('status')}}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products as $key=>$data)
                            <tr>
                                <td>{{$products->firstItem()+$key}}</td>
                                <td>
                                    <div class="p-name">
                                        <a href="{{route('admin.products.view',['addedBy'=>($data['added_by'] =='seller'?'vendor' : 'in-house'),'id'=>$data['id']])}}"
                                           class="media align-items-center gap-2 text-dark text-hover-primary">
                                            <span>{{\Illuminate\Support\Str::limit($data['name'],20)}}</span>
                                        </a>
                                    </div>
                                </td>
                                <td>{{ date('d M Y, h:i:s a', $data['updated_at'] ? strtotime($data['updated_at']) : null) }}</td>
                                <td class="text-center">{{$data['current_stock'] }}</td>
                                <td>
                                    <div class="text-center">
                                        @if($data['current_stock'] >= $stock_limit)
                                            <span class="badge text-bg-success badge-success">{{translate('in-Stock')}}</span>
                                        @elseif($data['current_stock']  <= 0)
                                            <span class="badge text-bg-warning badge-warning">{{translate('out_of_Stock')}}</span>
                                        @elseif($data['current_stock'] < $stock_limit)
                                            <span class="badge text-bg-info badge-info">{{translate('soon_Stock_Out')}}</span>
                                        @endif
                                    </div>
                                </td>
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
