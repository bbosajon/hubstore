'use strict';
function readURL(input) {
    $('#files').html("");
    for (let i = 0; i < input.files.length; i++) {
        if (input.files && input.files[i]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#files').append('<div class="col-md-2 col-sm-4 m-1"><img class="__empty-img" id="viewer" src="' + e.target.result + '" alt=""/></div>');
            }
            reader.readAsDataURL(input.files[i]);
        }
    }
}

$("#customFileUpload").change(function () {
    readURL(this);
});

$('#customZipFileUpload').change(function (e) {
    let fileName = e.target.files[0].name;
    $('#zipFileLabel').html(fileName);
});

$('.copy-path').on('click', function () {
    navigator.clipboard.writeText($(this).data('path'));
    toastMagic.success($('#get-file-copy-success-message').data('success'));
})

$(document).on("click", ".view-image-in-modal", function () {
    let imgTitle = $(this).data("title");
    let imgSrc = $(this).data("src");
    let imgLink = $(this).data("link");
    let imgPath = $(this).data("path");

    let modalElement = $('#image-modal-with-path');
    let modalTitle = modalElement.find('.image-modal-title');
    let modalImage = modalElement.find('.image-modal-img');
    let modalLink = modalElement.find('.image-modal-link');
    let modalCopyPath = modalElement.find('.image-modal-copy-path');

    if (imgSrc) {
        modalTitle.text(imgTitle);
        modalImage.attr("src", imgSrc);
        modalLink.attr("href", imgLink);
        modalCopyPath.attr("data-path", imgPath);
        modalElement.modal("show");
    }
});
