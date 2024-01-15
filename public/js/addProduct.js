document.addEventListener("DOMContentLoaded", function () {
    const addButton = document.querySelector("#addButton");
    const formContainer = document.querySelector("#formContainer");

    let isOpen = false;

    addButton.addEventListener("click", function () {
        if (isOpen) {
            formContainer.style.display = "none";
        } else {
            formContainer.style.display = "block";
        }
        
        isOpen = !isOpen;
    });
});
