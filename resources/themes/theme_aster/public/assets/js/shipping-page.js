"use strict";
$(document).ready(function () {
    mapsLoading();
    if ($('[name="shipping_method_id"]').prop('checked')) {
        let cardBody = $('[name="shipping_method_id"]:checked').parents('.card-header').siblings('.card-body')
        shippingMethodSelect(cardBody);
    }
    if ($('[name="billing_method_id"]').prop('checked')) {
        let cardBody = $('[name="billing_method_id"]:checked').parents('.card-header').siblings('.card-body')
        billingMethodSelect(cardBody);
    }
});

$('[name="shipping_method_id"]').on('change', function () {
    let cardBody = $(this).parents('.card-header').siblings('.card-body')
    shippingMethodSelect(cardBody);
})

function shippingMethodSelect(cardBody) {
    let updateThisAddress = $('.customize-text').data('update-this-address');
    let shippingMethodId = $('[name="shipping_method_id"]:checked').val();
    let shippingPerson = cardBody.find('.shipping-contact-person').text();
    let shippingPhone = cardBody.find('.shipping-contact-phone').text();
    let shippingAddress = cardBody.find('.shipping-contact-address').text();
    let shippingCity = cardBody.find('.shipping-contact-city').text();
    let shippingZip = cardBody.find('.shipping-contact-zip').text();
    let shippingCountry = cardBody.find('.shipping-contact-country').text();
    let shippingContactAddressType = cardBody.find('.shipping-contact-address-type').text();
    let updateAddress = `
                <input type="hidden" name="shipping_method_id" id="shipping-method-id" value="${shippingMethodId}">
                <input type="checkbox" name="update_address" id="update-address">${updateThisAddress}`;

    $('#name').val(shippingPerson);
    $('[name="phone"]').val(shippingPhone);
    $('#address').val(shippingAddress);
    $('#city').val(shippingCity);
    $('#zip').val(shippingZip);
    $('#select2-zip-container').text(shippingZip);
    $('#country').val(shippingCountry);
    $('#select2-country-container').text(shippingCountry);
    $('#address-type').val(shippingContactAddressType);
    $('#save-address-label').html(updateAddress);

    $('[name="latitude"]').val(cardBody.find('.shipping-contact-latitude-type').text());
    $('[name="longitude"]').val(cardBody.find('.shipping-contact-longitude-type').text());
}

$('[name="billing_method_id"]').on('change', function () {
    let cardBody = $(this).parents('.card-header').siblings('.card-body')
    billingMethodSelect(cardBody);
})

function billingMethodSelect(cardBody) {
    let updateThisAddress = $('.customize-text').data('update-this-address');
    let billingMethodId = $('[name="billing_method_id"]:checked').val();
    let billingPerson = cardBody.find('.billing-contact-name').text();
    let billingPhone = cardBody.find('.billing-contact-phone').text();
    let billingAddress = cardBody.find('.billing-contact-address').text();
    let billingCity = cardBody.find('.billing-contact-city').text();
    let billingZip = cardBody.find('.billing-contact-zip').text();
    let billingCountry = cardBody.find('.billing-contact-country').text();
    let billingContactAddressType = cardBody.find('.billing-contact-address-type').text();
    let updateAddressBilling = `
                <input type="hidden" name="billing_method_id" id="billing-method-id" value="${billingMethodId}">
                <input type="checkbox" name="update_billing_address" id="update-billing-address">${updateThisAddress}`;
    $('#billing-contact-person-name').val(billingPerson);
    $('[name="billing_phone"]').val(billingPhone);
    $('#billing_address').val(billingAddress);
    $('#billing-city').val(billingCity);
    $('#billing-zip').val(billingZip);
    $('#select2-billing_zip-container').text(billingZip);
    $('#billing-country').val(billingCountry);
    $('#select2-billing_country-container').text(billingCountry);
    $('#billing-address-type').val(billingContactAddressType);
    $('#save-billing-address-label').html(updateAddressBilling);

    $('[name="billing_latitude"]').val(cardBody.find('.billing-contact-latitude-type').text());
    $('[name="billing_longitude"]').val(cardBody.find('.billing-contact-longitude-type').text());
}

function toggleBillingAddress(){
    let checkSameAsShipping = $('#same-as-shipping-address').is(":checked");
    if (checkSameAsShipping) {
        $('#hide-billing-address').slideUp();
    } else {
        $('#hide-billing-address').slideDown();
    }
}

$(document).ready(function() {
    toggleBillingAddress();
});
$('#same-as-shipping-address').on('click', function() {
    toggleBillingAddress();
});

async function initAutoComplete() {
    let myLatLng = {
        lat: $('#shipping-address-location').data('latitude'),
        lng: $('#shipping-address-location').data('longitude'),
    };

    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
        center: myLatLng,
        zoom: 13,
        mapId: "roadmap",
    });

    let marker = new AdvancedMarkerElement({
        map,
        position: myLatLng,
    });

    marker.setMap(map);

    const geocoder = new google.maps.Geocoder();

    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
        const coordinates = mapsMouseEvent.latLng.toJSON();
        const latlng = new google.maps.LatLng(coordinates.lat, coordinates.lng);

        marker.position = { lat: coordinates.lat, lng: coordinates.lng };
        map.panTo(latlng);

        document.getElementById('latitude').value = coordinates.lat;
        document.getElementById('longitude').value = coordinates.lng;

        geocoder.geocode({ latLng: latlng }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK && results[1]) {
                const address = results[1].formatted_address;
                document.getElementById('address').value = address;
                document.getElementById("pac-input").value = address;
            }
        });
    });

    const input = document.getElementById("pac-input");
    const searchBox = new google.maps.places.SearchBox(input);

    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    map.addListener("bounds_changed", () => searchBox.setBounds(map.getBounds()));

    let markers = [];

    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;

        // Clear old markers
        markers.forEach(m => m.setMap(null));
        markers = [];

        const bounds = new google.maps.LatLngBounds();

        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) return;
            input.value = place.formatted_address || place.name;

            const mrkr = new AdvancedMarkerElement({
                map,
                title: place.name,
                position: place.geometry.location,
            });

            google.maps.event.addListener(mrkr, "click", function () {
                const pos = this.position;
                document.getElementById('latitude').value = pos.lat;
                document.getElementById('longitude').value = pos.lng;
            });

            markers.push(mrkr);

            if (place.geometry.viewport) bounds.union(place.geometry.viewport);
            else bounds.extend(place.geometry.location);
        });

        map.fitBounds(bounds);
    });
}

async function billingMap() {
    let myLatLng = {
        lat: $('#shipping-address-location').data('latitude'),
        lng: $('#shipping-address-location').data('longitude'),
    };

    const { Map } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

    const map = new google.maps.Map(document.getElementById("billing-location-map-canvas"), {
        center: myLatLng,
        zoom: 13,
        mapId: "roadmap",
    });

    let marker = new AdvancedMarkerElement({
        map,
        position: myLatLng,
    });

    marker.setMap(map);

    const geocoder = new google.maps.Geocoder();


    google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
        const coordinates = mapsMouseEvent.latLng.toJSON();
        const latlng = new google.maps.LatLng(coordinates.lat, coordinates.lng);

        marker.position = { lat: coordinates.lat, lng: coordinates.lng };
        map.panTo(latlng);

        document.getElementById('billing-latitude').value = coordinates.lat;
        document.getElementById('billing-longitude').value = coordinates.lng;

        geocoder.geocode({ latLng: latlng }, function (results, status) {
            if (status === google.maps.GeocoderStatus.OK && results[1]) {
                const address = results[1].formatted_address;
                document.getElementById('billing_address').value = address;
                document.getElementById("pac-input-billing").value = address;
            }
        });
    });

    const input = document.getElementById("pac-input-billing");
    const searchBox = new google.maps.places.SearchBox(input);

    map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
    map.addListener("bounds_changed", () => searchBox.setBounds(map.getBounds()));

    let markers = [];

    searchBox.addListener("places_changed", () => {
        const places = searchBox.getPlaces();
        if (places.length === 0) return;

        markers.forEach(m => m.setMap(null));
        markers = [];

        const bounds = new google.maps.LatLngBounds();

        places.forEach((place) => {
            if (!place.geometry || !place.geometry.location) return;

            input.value = place.formatted_address || place.name;

            const mrkr = new AdvancedMarkerElement({
                map,
                title: place.name,
                position: place.geometry.location,
            });

            google.maps.event.addListener(mrkr, "click", function () {
                const pos = this.position;
                document.getElementById('billing-latitude').value = pos.lat;
                document.getElementById('billing-longitude').value = pos.lng;
            });

            markers.push(mrkr);

            if (place.geometry.viewport) bounds.union(place.geometry.viewport);
            else bounds.extend(place.geometry.location);
        });

        map.fitBounds(bounds);
    });
}

$(document).on("keydown", "input", function (e) {
    if (e.which === 13) e.preventDefault();
});

function mapsLoading() {
    try {
        initAutoComplete();
    } catch (error) {
    }
    try {
        billingMap();
    } catch (error) {
    }
}

$('#proceed-to-next-action').on('click', function () {
    let physicalProduct = $('#physical-product').val();
    let billingAddressSameAsShipping = $('#same-as-shipping-address').is(":checked");
    if (physicalProduct === 'yes') {
        let allAreFilled = true;
        document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
            if (!allAreFilled) return;
            if (!i.value) allAreFilled = false;
            if (i.type === "radio") {
                let radioValueCheck = false;
                document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                    if (r.checked) radioValueCheck = true;
                });
                allAreFilled = radioValueCheck;
            }
        });
        let allAreFilledShipping = true;
        if (billingAddressSameAsShipping !== true && $('#billing-input-enable').val() === 1) {
            document.getElementById("billing-address-form").querySelectorAll("[required]").forEach(function (i) {
                if (!allAreFilledShipping) return;
                if (!i.value) allAreFilledShipping = false;
                if (i.type === "radio") {
                    let radioValueCheck = false;
                    document.getElementById("billing-address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                        if (r.checked) radioValueCheck = true;
                    });
                    allAreFilledShipping = radioValueCheck;
                }
            });
        }
    } else {
        let billingAddressSameAsShipping = false;
    }

    let redirectUrl = $(this).data('checkout-payment');
    let formUrl = $(this).data('goto-checkout');

    let isCheckCreateAccount = $('#is_check_create_account');
    let customerPassword = $('#customer_password');
    let customerConfirmPassword = $('#customer_confirm_password');

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
        },
    });
    $.post({
        url: formUrl,
        data: {
            physical_product: physicalProduct,
            shipping: physicalProduct === 'yes' ? $('#address-form').serialize() : null,
            billing: $('#billing-address-form').serialize(),
            billing_addresss_same_shipping: billingAddressSameAsShipping,
            is_check_create_account: isCheckCreateAccount && isCheckCreateAccount.prop("checked") ? 1 : 0,
            customer_password: customerPassword ? customerPassword.val() : null,
            customer_confirm_password: customerConfirmPassword ? customerConfirmPassword.val() : null,
        },

        beforeSend: function () {
            $('#loading').addClass('d-grid');
        },
        success: function (data) {
            if (data.errors) {
                for (let i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                location.href = redirectUrl;
            }
        },
        complete: function () {
            $('#loading').removeClass('d-grid');
        },
        error: function (data) {
            let errorMessage = data.responseJSON.errors;
            toastr.error(errorMessage, {
                CloseButton: true,
                ProgressBar: true
            });
        }
    });
});

$('#is_check_create_account').on('change', function() {
    if($(this).is(':checked')) {
        $('.is_check_create_account_password_group').fadeIn();
    } else {
        $('.is_check_create_account_password_group').fadeOut();
    }
});
