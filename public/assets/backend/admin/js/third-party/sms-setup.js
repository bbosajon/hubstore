
let paymentGatewayPublishedStatus = $('#payment-gateway-published-status').data('status');

if (paymentGatewayPublishedStatus?.toString() === 'true') {
    'use strict';
    let smsGatewayCards = $('#sms-gateway-cards');
    smsGatewayCards.find('input').each(function () {
        $(this).attr('disabled', true);
    });
    smsGatewayCards.find('select').each(function () {
        $(this).attr('disabled', true);
    });
    smsGatewayCards.find('.switcher_input').each(function () {
        $(this).removeAttr('checked', true);
    });
    smsGatewayCards.find('button').each(function () {
        $(this).attr('disabled', true);
    });
}

function getRecaptchaSMSSessionCode(sessionKey, inputSelector) {

    try {
        document.getElementById("default_sms_recaptcha_id").src = $('.get-recaptcha-session-auto-fill').data("link") + "/" + "?sessionKey=adminSMSRecaptchaSessionKey&" + Math.random();

        let routegetRecaptchaSessionCode = $('#route-g-recaptcha-session-store');
        if (routegetRecaptchaSessionCode.data('mode').toString() === 'dev') {
            let string = '.';
            let intervalId = setInterval(() => {
                if (string === '......') {
                    string = '.';
                }
                string = string + '.';
                $(inputSelector).val(string);
            }, 100);

            setTimeout(() => {
                clearInterval(intervalId);
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="_token"]').attr("content"),
                    },
                });
                $.ajax({
                    type: "POST",
                    url: $('#route-g-recaptcha-session-store').data('route'),
                    data: {
                        _token: $('meta[name="_token"]').attr("content"),
                        sessionKey: sessionKey,
                    },
                    success: function (response) {
                        $(inputSelector).val(response?.code);
                    },
                });
            }, 1000);
        }
    } catch (e) {
    }
}

$('.get-recaptcha-session-auto-fill').each(function () {
    getRecaptchaSMSSessionCode($(this).data('session'), $(this).data('input'))
});

$('.get-recaptcha-session-auto-fill').on('click', function () {
    getRecaptchaSMSSessionCode($(this).data('session'), $(this).data('input'));
});
