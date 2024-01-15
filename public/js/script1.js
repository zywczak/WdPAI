
        document.addEventListener('DOMContentLoaded', function () {
            // Pobierz wartość ze spana
            var userSpan = document.querySelector('header > div:nth-child(2) > span');
            var isUserLoggedIn = userSpan.innerHTML.trim() !== '';

            // Jeśli użytkownik nie jest zalogowany
            if (!isUserLoggedIn) {
                userSpan.style.display = 'none';
                document.querySelector('header > div:nth-child(2) > a[href="logout"]').style.display = 'none';
                document.querySelector('header > div:nth-child(2) > a[href="cart"]').style.display = 'none';
                document.querySelector('header > div:nth-child(2) > img:first-child').outerHTML = '<a href="login"><img src="../../public/img/login.png" alt="login"></a>';

                var coolerContainers = document.querySelectorAll("main > div");
                coolerContainers.forEach(function(container) {
                    var toCartElement = container.querySelector(".to-cart");
                    if (toCartElement) {
                        toCartElement.style.display = "none";
                    }
                });
            }
        });