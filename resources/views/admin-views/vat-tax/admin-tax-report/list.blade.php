@extends('layouts.admin.app')

@section('title', translate('Admin_Tax_Report'))

@push('css_or_js')
    <style>
        /*css Code*/
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-2 align-items-center mb-20">
            <h2 class="fs-20 mb-0">
                 {{ translate('Tax_Report') }}
            </h2>
        </div>
        <form action="" method="post">
            <div class="card card-body d-flex flex-column gap-20 mb-20">
                <div>
                    <h3 class="fs-18 mb-1">{{ translate('Generate_Tax_Report') }}</h3>
                    <p class="fs-12 mb-0">{{ translate('to_generate_you_tax_report_please_select_&_input_following_field_and_submit_for_the_result.') }}</p>
                </div>
                <div class="bg-section rounded p-12 p-sm-20">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="">
                                <label class="form-label" for="">{{ translate('date_range_type') }}</label>
                                <select class="custom-select" name="">
                                    <option value="1" selected>{{ translate('this_fiscal_year') }}</option>
                                    <option value="2">{{ translate('test') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="">
                                <label class="form-label" for="">{{ translate('select_how_to_calculate_tax') }}</label>
                                <select class="custom-select" id="select_calc_tax" name="">
                                    <option value="1" data-target=".same_tax_for_all_content" selected>{{ translate('same_tax_for_all_income_source') }}</option>
                                    <option value="2" data-target=".diff_tax_for_diff_content">{{ translate('different_tax_for_different_income_source') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 same_tax_for_all_content">
                            <div class="">
                                <label class="form-label" for="">{{ translate('Select_Vat/Tax_Rate') }}</label>
                                <select class="custom-select multiple-select2" id="tax_rate" name="" multiple="multiple" data-max-length="5" data-placeholder="{{ translate('type_&_Select_Vat/Tax_Rate') }}">
                                    <option value="1">VAT (5%)</option>
                                    <option value="2">GST (7%)</option>
                                    <option value="3">Option 03</option>
                                    <option value="4">Option 04</option>
                                    <option value="5">Option 05</option>
                                    <option value="6">Option 06</option>
                                    <option value="7">Option 07</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 diff_tax_for_diff_content">
                            <div class="">
                                <label class="form-label" for="">{{ translate('tax_on_order_commission') }}</label>
                                <select class="custom-select multiple-select2" id="tax_on_order_commission" name="" multiple="multiple" data-max-length="5" data-placeholder="{{ translate('type_&_select_tax_on_order_commission') }}">
                                    <option value="1">Income Tax (15%)</option>
                                    <option value="2">Option 02</option>
                                    <option value="3">Option 03</option>
                                    <option value="4">Option 04</option>
                                    <option value="5">Option 05</option>
                                    <option value="6">Option 06</option>
                                    <option value="7">Option 07</option>
                                    <option value="8">Option 08</option>
                                    <option value="9">Option 09</option>
                                    <option value="10">Option 010</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 diff_tax_for_diff_content">
                            <div class="">
                                <label class="form-label" for="">{{ translate('tax_on_delivery_charge') }}</label>
                                <select class="custom-select multiple-select2" name="" multiple="multiple" data-max-length="5" data-placeholder="{{ translate('type_&_Select_Vat/Tax_Rate') }}">
                                    <option value="1">VAT (5%)</option>
                                    <option value="2">GST (7%)</option>
                                    <option value="3">Option 03</option>
                                    <option value="4">Option 04</option>
                                    <option value="5">Option 05</option>
                                    <option value="6">Option 06</option>
                                    <option value="7">Option 07</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-wrap justify-content-end gap-3 tax_report_action">
                    <button type="reset" class="btn btn-secondary min-w-120">{{ translate('Reset') }}</button>
                    <button type="submit" class="btn btn-primary min-w-120">
                        {{ translate('submit') }}
                    </button>
                </div>
            </div>
        </form>
        <div class="card card-body mb-20">
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="d-flex align-items-center h-100">
                        <a class="w-100 h-100 bg-info-dark bg-opacity-10 d-flex align-items-center gap-2 justify-content-between p-3 rounded-10"
                            href="#">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="30" class="aspect-1" src="{{ dynamicAsset(path: 'public/assets/back-end/img/total-order-amount.png') }}" alt="">
                                <div class="flex-grow-1 d-flex gap-2 align-items-center">
                                    <span class="line-1 text-body mb-0" title="{{ translate('Total_Income') }}">{{ translate('Total_Income') }}</span>
                                    <span class="overflow-wrap-anywhere fw-bold text-info h2 mb-0">$ 12,345.25</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="d-flex align-items-center h-100">
                        <a class="w-100 h-100 bg-warning-dark bg-opacity-10 d-flex align-items-center gap-2 justify-content-between p-3 rounded-10"
                            href="#">
                            <div class="d-flex gap-3 align-items-center">
                                <img width="30" class="aspect-1" src="{{ dynamicAsset(path: 'public/assets/back-end/img/total-vat-amount.png') }}" alt="">
                                <div class="flex-grow-1 d-flex gap-2 align-items-center">
                                    <span class="line-1 text-body mb-0" title="{{ translate('Total_Tax') }}">{{ translate('Total_Tax') }}</span>
                                    <span class="overflow-wrap-anywhere fw-bold text-warning-dark h2 mb-0">$325.00</span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body d-flex flex-column gap-20">
                <div class="d-flex justify-content-between align-items-center gap-20 flex-wrap">
                    <h3 class="mb-0">
                        {{ translate('Tax_Report_List') }}
                        <span class="badge text-dark bg-body-secondary fw-semibold rounded-50">23</span>
                    </h3>
                    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-sm-end flex-grow-1">
                        <div class="flex-grow-1 max-w-300 min-w-100-mobile">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group">
                                    <input id="" type="search" name="searchValue" class="form-control"
                                        placeholder="{{ translate('search_by_tax_name') }}"
                                        value="{{ request('searchValue') }}">
                                    <div class="input-group-append search-submit">
                                        <button type="submit">
                                            <i class="fi fi-rr-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="dropdown">
                            <a type="button" class="btn btn-outline-primary"
                                href="{{ route('admin.category.export',['searchValue'=>request('searchValue')]) }}">
                                <i class="fi fi-sr-inbox-in"></i>
                                <span class="fs-12">{{ translate('export') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-borderless align-middle text-dark">
                        <thead class="text-capitalize">
                            <tr>
                                <th>{{ translate('SL') }}</th>
                                <th>{{ translate('Income_Source') }}</th>
                                <th>{{ translate('Total_Income') }}</th>
                                <th>{{ translate('Tax_Amount') }}</th>
                                <th class="text-center">{{ translate('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="5">
                                    <div class="d-flex justify-content-center align-items-center py-5">
                                        <div class="text-center">
                                            <img width="60" class="aspect-1 mb-30" src="{{ dynamicAsset(path: 'public/assets/back-end/img/tax.png') }}" alt="">
                                            <h4 class="mb-2">{{ translate('no_tax_report_generated') }}</h4>
                                            <p class="fs-12 text-body fw-medium max-w-500 mb-0">
                                                {{ translate('to_generate_your_tax_report_please_select_&_input_above_field_and_submit_for_the_result.') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="line-1 max-w-200">Vendor Commission Vendor Commission Vendor Commission</div>
                                </td>
                                <td>$ 1,830.25</td>
                                <td>
                                    <div>
                                        <div class="d-flex gap-1">
                                            <span class="min-w-120"> {{ translate('Total_Tax') }} (20%)</span>:
                                            <span>$ 160.00</span>
                                        </div>
                                        <div class="d-flex gap-1 fs-12 text-body">
                                            <span class="min-w-120"> {{ translate('VAT') }} (5%)</span>:
                                            <span>$ 50.00</span>
                                        </div>
                                        <div class="d-flex gap-1 fs-12 text-body">
                                            <span class="min-w-120"> {{ translate('GST') }} (15%)</span>:
                                            <span>$ 110.00</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-3">
                                        <button type="button" class="btn btn-outline-info btn-outline-info-dark icon-btn">
                                            <i class="fi fi-sr-eye"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-responsive mt-4">
                    <div class="d-flex justify-content-lg-end">

                        <nav>
                            <ul class="pagination">
                                <li class="page-item disabled" aria-disabled="true" aria-label="« Previous">
                                    <span class="page-link" aria-hidden="true">‹</span>
                                </li>
                                <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                                <li class="page-item"><a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=2">2</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=3">3</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=4">4</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=5">5</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=6">6</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=7">7</a>
                                </li>
                                <li class="page-item"><a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=8">8</a>
                                </li>
                                <li class="page-item">
                                    <a class="page-link"
                                        href="http://localhost/Backend-6Valley-eCommerce-CMS/admin/report/inhouse-product-sale?added_by=in_house&amp;page=2"
                                        rel="next" aria-label="Next »">›</a>
                                </li>
                            </ul>
                        </nav>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        'use strict';
        $(document).ready(function () {
            function selectCalcTax() {
                $('.same_tax_for_all_content, .diff_tax_for_diff_content').addClass('d-none');

                let targetSelector = $('#select_calc_tax option:selected').data('target');
                if (targetSelector) {
                    $(targetSelector).removeClass('d-none');
                }

                actionBtnDisable();
            }

            function actionBtnDisable() {
                const $wrapper   = $('.tax_report_action');
                const $submitBtn = $wrapper.find('button[type="submit"]');
                const $resetBtn  = $wrapper.find('button[type="reset"]');

                $submitBtn.prop('disabled', true);
                $resetBtn.prop('disabled', true);

                let selectedVal = null;

                if (!$('.same_tax_for_all_content').hasClass('d-none')) {
                    selectedVal = $('#tax_rate').val();
                } else if (!$('.diff_tax_for_diff_content').hasClass('d-none')) {
                    selectedVal = $('#tax_on_order_commission').val();
                }

                if (selectedVal && selectedVal.length > 0) {
                    $submitBtn.prop('disabled', false);
                    $resetBtn.prop('disabled', false);
                }
            }

            selectCalcTax();
            actionBtnDisable();

            $('#select_calc_tax').on('change', selectCalcTax);
            $('#tax_rate, #tax_on_order_commission').on('change', actionBtnDisable);
            $('.tax_report_action button[type="reset"]').on('click', () => {
                setTimeout(actionBtnDisable, 100);
            });
        });

    </script>

@endpush
