"use strict";
$(function () {
    let systemAcceptableMaxFileSize = $('#imageUploadMaxSize').data('max-size');
    $(".coba").spartanMultiImagePicker({
        fieldName: 'fileUpload[]',
        maxCount: 5,
        maxFileSize: Number(systemAcceptableMaxFileSize) * 1024 * 1024,
        rowHeight: '150px',
        allowedExt: 'png|jpg|jpeg|webp',
        groupClassName: 'col-md-4',
        placeholderImage: {
            image: $('#get-place-holder-image').data('src'),
            width: '100%'
        },
        dropFileLabel: "Drop Here",
        onAddRow: function (index, file) {

        },
        onRenderedPreview: function (index) {

        },
        onRemoveRow: function (index) {

        },
        onExtensionErr: function (index, file) {
            toastr.error('Invalid file format. Only PNG, JPG, JPEG files are allowed.');
        },
        onSizeErr: function (index, file) {
            toastr.error(file?.name + ` exceeds ${systemAcceptableMaxFileSize}MB`);
        }

    })
});
$(function () {
    let systemAcceptableMaxFileSize = $('#imageUploadMaxSize').data('max-size');
    $(".coba_refund").spartanMultiImagePicker({
            fieldName: 'images[]',
            maxCount: 5,
            maxFileSize: Number(systemAcceptableMaxFileSize) * 1024 * 1024,
            rowHeight: '70px',
            groupClassName: 'upload-custom-img',
            allowedExt: 'png|jpg|jpeg|webp',
            placeholderImage: {
                image: $('#get-place-holder-image').data('src'),
            width: '100%'
        },
        dropFileLabel: "{{translate('drop_here')}}",
        onAddRow: function (index, file) {

    },
    onRenderedPreview: function (index) {

    },
    onRemoveRow: function (index) {

    },
    onExtensionErr: function () {
        toastr.error('Invalid file format. Only PNG, JPG, JPEG files are allowed.');
    }, onSizeErr: function (index, file) {
            toastr.error(file?.name + ` exceeds ${systemAcceptableMaxFileSize}MB`);
        }
    });
});

$('.remove-mask-img').on('click', function(){
    $('.show-more--content').removeClass('active')
})
