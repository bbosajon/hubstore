"use strict";

$(document).ready(function() {
    let initialSeconds = parseInt($('.verifyCounter').data('second'));
    if (initialSeconds > 0) {
        startCountdown(initialSeconds);
    }
});

function startCountdown(seconds) {
    let counter = $('.verifyCounter');
    let remainingSeconds = seconds;

    function tick() {
        let m = Math.floor(remainingSeconds / 60);
        let s = remainingSeconds % 60;
        counter.html(m + ":" + (s < 10 ? "0" : "") + String(s));
        remainingSeconds--;

        if (remainingSeconds >= 0) {
            $('.otp-resend-btn').attr('disabled', true);
            $(".resend-otp-custom").slideDown();
            setTimeout(tick, 1000);
        } else {
            $('.otp-resend-btn').removeAttr('disabled');
            counter.html("0:00");
            $(".resend-otp-custom").slideUp();
        }
    }
    tick();
}

$('#customer-verify').on('submit', function (event) {
    event.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        dataType: "json",
        data: $(this).serialize(),
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (data.status === 'success') {
                $('#otp_form_section').addClass('d-none');
                $('#success_message').removeClass('d-none');
                $('#loginModal').modal('show');
                toastr.success(data.message);
            } else {
                toastr.error(data.message);
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
});

$('#resend-otp').click(function () {
    $('input.otp-field').val('');
    let userId = $(this).data('field') === 'identity' ? $('input[name="identity"]').val(): $('input[name="id"]').val();
    let url = $(this).data('route') ;
    if ($(this).data('field') === 'identity') {
        sendAjaxRequest(url,{identity: userId });
    } else {
        sendAjaxRequest(url,{user_id: userId });
    }
});

document.querySelectorAll('.otp-resend-btn').forEach(function(button) {
    button.addEventListener('click', function() {
        let route = this.getAttribute('data-url');
        let form = this.closest('form');
        if (form) {
            form.setAttribute('action', route);
        }
    });
});

function sendAjaxRequest(url,responseData)
{
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
        }
    });
    $.ajax({
        url: url,
        method: 'POST',
        dataType: 'json',
        data: responseData,
        beforeSend: function () {
            $("#loading").addClass("d-grid");
        },
        success: function (data) {
            if (parseInt(data.status) === 1) {
                startCountdown(data.new_time);
                toastr.success($('#get-resend-otp-text').data('success'));
            } else {
                toastr.error($('#get-resend-otp-text').data('error'));
            }
        },
        complete: function () {
            $("#loading").removeClass("d-grid");
        },
    });
}
