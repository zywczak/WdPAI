document.addEventListener('DOMContentLoaded', function () {
    var previewImageInputs = document.querySelectorAll('.form input[name="photo"]');

    previewImageInputs.forEach(function (input) {
        input.addEventListener('change', function () {
            var preview = this.closest('.form').querySelector('.preview');
            displayPreviewImage(this, preview);
        });
    });
});

function displayPreviewImage(input, preview) {
    var file = input.files[0];
    var reader = new FileReader();

    reader.onloadend = function () {
        preview.src = reader.result;
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.src = "../../public/img/default-preview-image.jpg";
    }
}