@extends('layouts.admin.app')

@section('title', translate('sub_Category'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="h1 mb-0 d-flex gap-2">
                <img src="{{ dynamicAsset(path: 'public/assets/back-end/img/brand-setup.png') }}" alt="">
                {{ translate('Sub_Category_Setup') }}
            </h2>
        </div>

        <div class="row mt-20" id="cate-table">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-20">
                        <div class="d-flex justify-content-between align-items-center gap-20 flex-wrap">
                            <h3 class="mb-0">
                                {{ translate('Sub_Category_List') }}
                                <span class="badge text-dark bg-body-secondary fw-semibold rounded-50 mx-1">
                                    {{ $categories->total() }}
                                </span>
                            </h3>
                            <div class="d-flex flex-wrap gap-3 align-items-center">
                                <form action="{{ url()->current() }}" method="GET" class="flex-grow-1">
                                    <div class="input-group min-w-300">
                                        <input id="" type="search" name="searchValue" class="form-control"
                                               placeholder="{{ translate('search_by_sub_category_name') }}"
                                               value="{{ request('searchValue') }}">
                                        <div class="input-group-append search-submit">
                                            <button type="submit">
                                                <i class="fi fi-rr-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                <buton type="btn" class="btn {{ request('sort_by') || request('old_categories') ? 'btn-primary' : 'btn-outline-primary' }}" data-bs-toggle="offcanvas"
                                       data-bs-target="#offcanvasSubCatFilter">
                                    <i class="fi fi-rr-bars-filter"></i> {{ translate('Filter') }}
                                </buton>

                                <div class="dropdown">
                                    <a type="button" class="btn btn-outline-primary"
                                       href="{{ route('admin.sub-category.export', [
                                                'searchValue' => request('searchValue'),
                                                'sort_by' => request('sort_by'),
                                                'categories' => request('categories'),
                                                'old_categories' => request('old_categories'),
                                                ]) }}">
                                        <i class="fi fi-sr-inbox-in"></i>
                                        <span class="fs-12">{{ translate('export') }}</span>
                                    </a>
                                </div>

                                <div>
                                    <button class="btn btn-primary" title="{{ translate('Add') }}"
                                            data-bs-toggle="offcanvas" href="#categoryAddOffcanvas"
                                    > + {{ translate('Add_Sub_Category') }}</button>
                                </div>

                            </div>
                        </div>
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-borderless table-thead-bordered table-nowrap align-middle">
                                <thead class="text-capitalize">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    @if (theme_root_path() == 'theme_aster')
                                        <th>{{ translate('sub_category_Image') }}</th>
                                    @endif
                                    <th>{{ translate('Sub_Category_Name') }}</th>
                                    <th>{{ translate('Main_Category_Name') }}</th>
                                    <th class="text-center">{{ translate('priority') }}</th>
                                    <th class="text-center">{{ translate('action') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($categories as $key => $category)
                                    <tr>
                                        <td>{{ $categories->firstItem() + $key }}</td>
                                        @if (theme_root_path() == 'theme_aster')
                                            <td class="text-center">
                                                <div
                                                    class="avatar-60 d-flex align-items-center rounded overflow-hidden">
                                                    <img class="w-100 h-100 object-fit-cover" alt=""
                                                         src="{{ getStorageImages(path: $category->icon_full_url , type: 'backend-basic') }}">
                                                </div>
                                            </td>
                                        @endif
                                        <td>
                                            <h6 class="fs-14">{{ $category['defaultname'] }}</h6>
                                            <span class="fs-12">{{ translate('ID') }}  #{{ $category['id'] }}</span>
                                        </td>
                                        <td class="max-w-120 text-wrap">{{$category?->parent?->defaultname ?? translate('category_not_found') }}</td>
                                        <td class="text-center">{{ $category['priority']}}</td>
                                        <td>
                                            <div class="d-flex justify-content-center gap-3">
                                                <a class="btn btn-outline-info icon-btn edit" title="{{ translate('Edit') }}"
                                                   data-bs-toggle="offcanvas" href="#categoryEditOffcanvas-{{ $category['id'] }}">
                                                    <i class="fi fi-sr-pencil"></i>
                                                </a>
                                                <a class="btn btn-outline-danger icon-btn category-delete-button"
                                                   title="{{ translate('delete') }}"
                                                   id="{{ $category['id']}}">
                                                    <i class="fi fi-rr-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="table-responsive mt-4">
                            <div class="d-flex justify-content-lg-end">
                                {{ $categories->links() }}
                            </div>
                        </div>

                        @if(count($categories)==0)
                            @include('layouts.admin.partials._empty-state',['text'=>'no_sub_category_found'],['image'=>'default'])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include("admin-views.category.offcanvas._subcategory-filter")

    @include("admin-views.category.offcanvas._category-add", ['categoryType' => 'sub_category'])
    @foreach($categoriesWithTrans as $key => $category)
        @include("admin-views.category.offcanvas._category-edit", ['category' => $category])
    @endforeach

    <span id="route-admin-category-delete" data-url="{{ route('admin.sub-category.delete') }}"></span>
@endsection

@push('script')
    <script src="{{ dynamicAsset(path: 'public/assets/backend/admin/js/products/products-management.js') }}"></script>
@endpush
