// Przykład używając jQuery
$(document).ready(function() {
    $('.addToCart').on('click', function(e) {
        e.preventDefault();

        // Pobierz id produktu z atrybutu data-product-id
        var productId = $(this).data('product-id');

        // Wyślij żądanie AJAX do dodania produktu do koszyka
        $.ajax({
            type: 'POST',
            url: '/addToCart',
            data: { product_id: productId },
            success: function(response) {
                // Odpowiedź serwera w formie JSON
                var result = JSON.parse(response);

                alert(result.message);  // Możesz dostosować komunikat lub zastosować inne działania
            },
            error: function() {
                // Obsługa błędów AJAX
                alert('Failed to communicate with the server.');
            }
        });
    });
});
