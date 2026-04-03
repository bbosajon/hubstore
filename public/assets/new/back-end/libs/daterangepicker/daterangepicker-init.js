"use strict";

$(document).ready(function() {
    // --- Date range Picker ---
    $(".js-daterangepicker").daterangepicker(
        {
            autoUpdateInput: false,
            locale: {
                format: "DD MMM YYYY"
            }
        },
        function(start, end) {
            $("#reportrange span").html(
                start.format("DD MMM YYYY") + " - " + end.format("DD MMM YYYY")
            );
        }
    );

    $(".js-daterangepicker_till_current").daterangepicker({ maxDate: moment() })

    $(".js-daterangepicker-times-sec").daterangepicker({
        timePicker: true,
        timePickerSeconds: true,
        timePicker24Hour: false,
        locale: {
            format: "MM/DD/YYYY hh:mm:ss A"
        }
    });

    $(".js-daterangepicker-with-range").daterangepicker({
        timePicker: false,
        autoUpdateInput: false,
        ranges: {
            Today: [moment(), moment()],
            Yesterday: [
                moment().subtract(1, "days"),
                moment().subtract(1, "days")
            ],
            "Last 7 Days": [moment().subtract(6, "days"), moment()],
            "Last 30 Days": [moment().subtract(29, "days"), moment()],
            "This Month": [moment().startOf("month"), moment().endOf("month")],
            "Last Month": [
                moment()
                    .subtract(1, "month")
                    .startOf("month"),
                moment()
                    .subtract(1, "month")
                    .endOf("month")
            ]
        },
        alwaysShowCalendars: true
    });

    $(".js-daterangepicker-with-range").on("apply.daterangepicker", function(
        ev,
        picker
    ) {
        $(this).removeAttr("readonly");
        $(this).removeClass("cursor-pointer");
        $(this).val(
            picker.startDate.format("MM/DD/YYYY") +
            " - " +
            picker.endDate.format("MM/DD/YYYY")
        );
    });

    $(".js-daterangepicker-time-only").daterangepicker(
        {
            timePicker: true,
            timePickerSeconds: true,
            timePicker24Hour: false,
            locale: {
                format: "hh:mm:ss A"
            },
            opens: "center"
        },
        function(start, end) {
            updateTimeRange(start, end);
        }
    );
    $(".js-daterangepicker-time-only").on("show.daterangepicker", function() {
        const picker = $(this).data("daterangepicker");
        if (picker) {
            picker.container.find(".calendar-table").hide();
        }
    });
    function updateTimeRange(start, end) {
        $(".js-daterangepicker-time-only").val(
            start.format("hh:mm:ss A") + " - " + end.format("hh:mm:ss A")
        );
    }

// ---- single date daterangepicker
    $(".js-daterangepicker_single-date-with-placeholder").daterangepicker({
        singleDatePicker: true,
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });
    $(".js-daterangepicker_single-date-with-placeholder-add-new-vendor").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minDate: moment(),
        autoUpdateInput: false,
        locale: {
           cancelLabel: 'Clear'
        }
    });

    $(".js-daterangepicker_single-date-with-placeholder, .js-daterangepicker_single-date-with-placeholder-add-new-vendor").on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
    });

    $(".js-daterangepicker_single-date-with-placeholder, .js-daterangepicker_single-date-with-placeholder-add-new-vendor").on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });
});
