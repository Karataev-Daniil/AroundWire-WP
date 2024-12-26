document.addEventListener('DOMContentLoaded', function () {
    var firstImage = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAGhJREFUSIljYBgFo4CuwMDIpN7AyKSeFD0spChmZGRsgDIbidXDRIoF5IChbwEjLgkDI5N6pDDHC/7//99w4dwZrPFCcx+QBAyNTf8bGpv+J0XP0I9kmltAUk7+//9/A43cMQpGAR4AANzJEfOOAm+9AAAAAElFTkSuQmCC";
    var secondImage = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAADZJREFUSIljYBgFo2AUjAKCgBGXhKGx6XUGBgYNokz5z7Dp/LnT/tikmMhz1ygYBaNgFFAVAAA5ZQYE1i79SgAAAABJRU5ErkJggg==";

    var faqTitles = document.querySelectorAll('.faq-title');

    faqTitles.forEach(function (title) {
        var content = title.nextElementSibling;

        if (content.classList.contains('show')) {
            content.style.maxHeight = content.scrollHeight + 'px';
        }

        title.addEventListener('click', function () {
            var image = title.querySelector('.toggle-image');

            if (content.classList.contains('show')) {
                content.classList.remove('show');
                image.src = firstImage;
                title.classList.remove('active');
                content.style.maxHeight = null;
            } else {
                content.classList.add('show');
                image.src = secondImage;
                title.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });
});