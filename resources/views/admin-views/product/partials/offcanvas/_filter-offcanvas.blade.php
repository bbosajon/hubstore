<form action="{{ route('admin.products.list', ['type' => request('type')]) }}" method="GET">
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasProductFilter"
        aria-labelledby="offcanvasProductFilterLabel" style="--bs-offcanvas-width: 500px;">
        <div class="offcanvas-header bg-body">
            <h3 class="mb-0">{{ translate('Filter') }}</h3>
            <button type="button" class="btn btn-circle bg-white text-dark fs-10" style="--size: 1.5rem;" data-bs-dismiss="offcanvas" aria-label="Close">
                <i class="fi fi-rr-cross"></i>
            </button>
        </div>
        <div class="offcanvas-body">
            @php
                $filterSections = ['sorting', 'product_type', 'product_status', 'brand', 'category'];
                if (request('type') == 'vendor' && (!in_array(request('request_status', ''), [1]))) {
                    $filterSections = ['sorting', 'product_type', 'brand', 'category'];
                }
            @endphp

            @if(request()->has('request_status'))
                <input type="hidden" name="request_status" value="{{ request('request_status') }}">
            @endif

            @include("admin-views.partials._product-filters-sections", [
                'filterSection' => $filterSections,
                'productBrands' => $brands,
                'productCategories' => $categories
            ])
        </div>
        <div class="offcanvas-footer shadow-popup">
            <div class="d-flex justify-content-center gap-3 bg-white px-3 py-2">
                <a class="btn btn-secondary w-100" href="{{ route('admin.products.list', ['type' => request('type')]) }}">
                    {{ translate('Clear_Filter') }}
                </a>
                <button type="submit" class="btn btn-primary w-100">
                    {{ translate('Apply') }}
                </button>
            </div>
        </div>
    </div>
</form>
