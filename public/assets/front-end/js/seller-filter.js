$('.product-list-filter-input').on('change', function () {
    const inputName = $(this).attr('name');
    const inputValue = $(this).val();
    const baseUrl = window.location.origin + window.location.pathname;
    const newUrl = new URL(baseUrl);
    if (inputValue) {
        newUrl.searchParams.set(inputName, inputValue);
    } else {
        newUrl.searchParams.delete(inputName);
    }
    window.location.href = newUrl.toString();
});
$("#search-brand").on("keyup", function () {
    let value = this.value.toLowerCase().trim();
    $("#lista1 div>li").show().filter(function () {
        return $(this).text().toLowerCase().trim().indexOf(value) == -1;
    }).hide();
});

$(".search-product-attribute").on("keyup", function () {
    let value = this.value.toLowerCase().trim();
    let container = $(this).closest('.search-product-attribute-container');
    let listItems = container.find(".attribute-list ul>li");
    let noDataText = container.find(".no-data-found");

    $(this).closest('.search-product-attribute-container').find(".attribute-list ul>li").show().filter(function () {
        return $(this).text().toLowerCase().trim().indexOf(value) == -1;
    }).hide();

    if (listItems.filter(":visible").length === 0) {
        noDataText.show();
    } else {
        noDataText.hide();
    }
});
