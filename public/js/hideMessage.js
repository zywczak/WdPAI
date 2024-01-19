document.addEventListener("DOMContentLoaded", function () {
    var messageDivs = document.querySelectorAll('.message');

    messageDivs.forEach(function (messageDiv) {
        setTimeout(function () {
            messageDiv.classList.add('hidden');
        }, 3000);
    });
});