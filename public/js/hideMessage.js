// Po załadowaniu strony
document.addEventListener("DOMContentLoaded", function () {
    // Znajdź elementy z klasą "message"
    var messageDivs = document.querySelectorAll('.message');

    // Dla każdego znalezionego elementu
    messageDivs.forEach(function (messageDiv) {
        // Ukryj element po 3 sekundach
        setTimeout(function () {
            messageDiv.classList.add('hidden');
        }, 3000);
    });
});