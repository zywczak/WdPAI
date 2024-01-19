document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.addToCart').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var productId = this.getAttribute('data-product-id');

            fetch('/addToCart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ product_id: productId }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                alert('Product successfully added to the cart: ' + data.message);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                alert('Failed to communicate with the server. Please try again later.');
            });
        });
    });
});
