/* connecting scripts for faqs blocks on landings */

/* common scripts for faqs blocks */
document.addEventListener('DOMContentLoaded', function() {
    var firstImage = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAAGhJREFUSIljYBgFo4CuwMDIpN7AyKSeFD0spChmZGRsgDIbidXDRIoF5IChbwEjLgkDI5N6pDDHC/7//99w4dwZrPFCcx+QBAyNTf8bGpv+J0XP0I9kmltAUk7+//9/A43cMQpGAR4AANzJEfOOAm+9AAAAAElFTkSuQmCC";
    var secondImage = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABHNCSVQICAgIfAhkiAAAADZJREFUSIljYBgFo2AUjAKCgBGXhKGx6XUGBgYNokz5z7Dp/LnT/tikmMhz1ygYBaNgFFAVAAA5ZQYE1i79SgAAAABJRU5ErkJggg==";
    
    var faqTitles = document.querySelectorAll('.faq-title');
    
    faqTitles.forEach(function(title) {
        title.addEventListener('click', function() {
            var content = title.nextElementSibling;
            var image = title.querySelector('.toggle-image');
            
            if (content.classList.contains('show')) {
                content.classList.remove('show');
                image.src = firstImage;
                title.classList.remove('active');
                content.style.maxHeight = null;
            } else {
                document.querySelectorAll('.faq-content.show').forEach(function(openContent) {
                    openContent.classList.remove('show');
                    var openImage = openContent.previousElementSibling.querySelector('.toggle-image');
                    openImage.src = firstImage;
                    openContent.previousElementSibling.classList.remove('active');
                    openContent.style.maxHeight = null;
                });
                
                content.classList.add('show');
                image.src = secondImage;
                title.classList.add('active');
                content.style.maxHeight = content.scrollHeight + 'px';
            }
        });
    });
});