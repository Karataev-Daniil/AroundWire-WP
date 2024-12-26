function openVideoModal(videoUrl) {
    const modal = document.getElementById("videoModal");
    const videoFrame = document.getElementById("videoFrame");

    if (modal.style.display === "flex") {
        closeVideoModal();
        return;
    }

    const autoplayUrl = videoUrl + (videoUrl.includes('?') ? '&' : '?') + 'autoplay=1';
    videoFrame.src = autoplayUrl;
    modal.style.display = "flex";
    lockScroll();

    setTimeout(function() {
        jQuery('.item-reviews').slick('slickPause');
    }, 100);
}

function lockScroll() {
    scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
    document.body.classList.add('no-scroll');
    document.body.style.top = `-${scrollPosition}px`;
}

function unlockScroll() {
    document.body.classList.remove('no-scroll');
    document.body.style.top = '';
    window.scrollTo(0, scrollPosition);
}


function closeVideoModal() {
    const modal = document.getElementById("videoModal");
    const videoFrame = document.getElementById("videoFrame");

    modal.style.display = "none";
    videoFrame.src = "";
    unlockScroll();
    jQuery('.item-reviews').slick('slickPlay');
}

jQuery(document).ready(function($) {
    function initSlider() {
        if (!$('.item-reviews').hasClass('slick-initialized2')) {
            $('.item-reviews').slick({
                infinite: true,
                slidesToShow: 4,
                slidesToScroll: 1,
                variableWidth: true,
                swipeToSlide: true,
                touchThreshold: 100,
                autoplay: true, 
                autoplaySpeed: 3000,
                arrows: false, 
                dots: false 
            });
        }
    }
    initSlider(); 

    // Stop and resume slider on hover
    $('.item-reviews').on('mouseover', function() {
        $(this).slick('slickPause');
    }).on('mouseout', function() {
        $(this).slick('slickPlay');
    });
});