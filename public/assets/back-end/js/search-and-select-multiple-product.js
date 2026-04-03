'use strict';
$('.search-product').on('keyup',function (){
    let name = $(this).val();
    if (name.length > 0) {
        $.get($('#get-search-product-route').data('action'), {searchValue: name}, (response) => {
            $('.search-result-box').empty().html(response.result);
        })
    }
})
let searchTimeout;
$('.search-all-type-product').on('click focus keyup', function () {
    let name = $(this).val();
    let dealId = $(this).data('deal-id');
    let $input = $(this);
    let $wrapper = $input.closest('.dropdown');
    let $dropdown = $wrapper.find('.dropdown-menu');

    clearTimeout(searchTimeout);

    if (name.length > 0) {
        searchTimeout = setTimeout(function() {
            let data = { searchValue: name };
            if (dealId) {
                data.deal_id = dealId;
            }
            $dropdown.addClass('show');
            $.get($('#get-search-all-type-product-route').data('action'), data, (response) => {
                $('.search-result-box').empty().html(response.result);
            });
        }, 1000);
    } else {
        $dropdown.removeClass('show');
        $('.search-result-box').empty();
    }
});

$(document).ready(function () {
    let searchTimeout;
    $(document).on('input focus keyup', '.search-product-for-clearance-sale', function () {
        let $input = $(this);
        let $wrapper = $input.closest('.select-clearance-product-search');
        let $dropdown = $wrapper.find('.dropdown-menu');
        let name = $input.val();
        clearTimeout(searchTimeout);
        if(name.length > 0){
            searchTimeout = setTimeout(function() {
                $dropdown.addClass('show');
                $.get($('#get-search-product-for-clearance-route').data('action'), { searchValue: name }, (response) => {
                    $wrapper.find('.search-result-box').html(response.result);
                });
            }, 1000);
        }else{
            $dropdown.removeClass('show');
            $('.search-result-box').empty();
        }
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.select-clearance-product-search').length) {
            $('.dropdown-menu').removeClass('show');
        }
    });
    $(document).on('keypress', '.search-product-for-clearance-sale', function (e) {
        if (e.which === 13) {
            e.preventDefault();
        }
    });
});

let selectProductSearch = $('.select-product-search');
let productIdsArray = [];
selectProductSearch.on('click', '.select-product-item', function () {
    let productId = $(this).find('.product-id').text();
    if(productIdsArray.indexOf(productId)){
        productIdsArray.push(productId);
        getProductDetails(productIdsArray);
    }


})
function removeSelectedProduct(){
    $('.remove-selected-product').on('click', function () {
        productIdsArray.splice(productIdsArray.indexOf($(this).data('product-id')));
        $(this).closest('.select-product-item').remove();
    });
}
$('.reset-selected-products').on('click',function (){
    productIdsArray = [];
    $('#selected-products').empty();
})

function getProductDetails(productIds){
    $.ajax({
        url: $('#get-multiple-product-details-route').data('action'),
        type: 'GET',
        data: { productIds: productIds },
        beforeSend: function () {
            $("#loading").fadeIn();
        },
        success: function(response) {
            $('#selected-products').empty().html(response.result);

            removeSelectedProduct();
        },
        complete: function () {
            $("#loading").fadeOut();
        },
    });

}

let selectClearanceProductSearch = $('.select-clearance-product-search');
let clearanceProductIdsArray = [];
selectClearanceProductSearch.on('click', '.select-clearance-product-item', function () {
    let productId = $(this).find('.product-id').text();
    if (clearanceProductIdsArray.indexOf(productId)) {
        clearanceProductIdsArray.push(productId);
        getClearanceProductDetails(clearanceProductIdsArray);
    }
    checkClearanceProductArray()

})

function removeSelectedClearanceProduct() {
    $('.remove-selected-clearance-product').on('click', function () {
        clearanceProductIdsArray.splice(clearanceProductIdsArray.indexOf($(this).data('product-id')));
        $(this).closest('.remove-selected-clearance-parent').remove();
        checkClearanceProductArray()
    });
}

function getClearanceProductDetails(productIds) {
    $.ajax({
        url: $('#get-multiple-clearance-product-details-route').data('action'),
        type: 'GET',
        data: {productIds: productIds},
        beforeSend: function () {
            $("#loading").fadeIn();
        },
        success: function (response) {
            $('#selected-products').empty().html(response.result);
            removeSelectedClearanceProduct();
        },
        complete: function () {
            $("#loading").fadeOut();
        },
    });

}

function checkClearanceProductArray() {
    if (clearanceProductIdsArray?.length > 0) {
        $('.search-and-add-product').hide();
    } else {
        $('.search-and-add-product').show();
    }
}

$(document).ready(function() {
    const $selectedProductsContainer = $('#selected-products');
    const $addProductsBtn = $('#add-products-btn');
    function toggleAddProductsButton() {
        $addProductsBtn.prop('disabled', $selectedProductsContainer.children().length === 0);
    }

    toggleAddProductsButton();

    const observer = new MutationObserver(toggleAddProductsButton);
    observer.observe($selectedProductsContainer[0], { childList: true });
});
