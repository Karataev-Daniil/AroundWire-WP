document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const popup = document.getElementById('form-popup');
    const closeButton = document.getElementById('close-popup');
    const formPopupButtons = document.querySelectorAll('.form-popup-button');
    const forms = popup.querySelectorAll('form');
    const inputs = popup.querySelectorAll('.input-block input');
    let scrollPosition = 0;

    // Lock page scroll while keeping scrollbar visible
    function lockScroll() {
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        body.style.overflow = 'hidden'; // Disable scrolling
    }

    // Unlock page scroll
    function unlockScroll() {
        body.style.overflow = ''; // Restore scrolling
        body.style.pointerEvents = ''; // Restore interaction
        window.scrollTo(0, scrollPosition); // Keep the page at the same scroll position
    }

    // Reset all forms in the popup
    function resetForms() {
        forms.forEach(form => form.reset());
    }

    // Add the default class to all inputs and remove active/filled classes
    function setDefaultClassToInputs() {
        inputs.forEach(input => {
            input.classList.remove('active', 'filled');
            input.classList.add('default');
            input.value = input.defaultValue;
        });
    }

    // Close popup function
    function closePopup() {
        unlockScroll();
        popup.classList.remove('popup-show');
        resetForms();
        setDefaultClassToInputs();
    }

    // Open popup on button click
    formPopupButtons.forEach(button => {
        button.addEventListener('click', function () {
            lockScroll();
            popup.classList.add('popup-show');
        });
    });

    // Close the popup when the close button is clicked
    closeButton.addEventListener('click', closePopup);
});
