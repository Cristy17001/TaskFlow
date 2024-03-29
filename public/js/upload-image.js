document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('upload-image-btn').addEventListener('click', function() {
        document.getElementById('fileInput').click();
    });
    const fileInput = document.querySelector('input[name="file"]');
    fileInput.addEventListener('change', function() {
        const form = document.getElementById('upload-image-form');
        form.submit();
    });
});