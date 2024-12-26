
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('video');
    const playPauseButton = document.getElementById('playPauseButton');
    const muteButton = document.getElementById('muteButton');
    const videoControls = document.querySelector('.video-controls');

    if (!video || !playPauseButton || !muteButton || !videoControls) {
        return;
    }

    const playImageSrc = "/wp-content/uploads/2024/08/Play-Button-1.svg";
    const pauseImageSrc = "/wp-content/uploads/2024/08/Stop-Button.svg";
    const muteImageSrc = "/wp-content/uploads/2024/08/Mute-Button.svg";
    const unmuteImageSrc = "/wp-content/uploads/2024/08/Unmute-Button.svg";

    let hideControlsTimeout;

    function updateButtonImage(button, newSrc) {
        const buttonImage = button.querySelector('img');
        buttonImage.classList.add('hide');
        setTimeout(() => {
            buttonImage.src = newSrc;
            buttonImage.classList.remove('hide');
        }, 300);
    }

    function setHideControlsTimeout() {
        clearTimeout(hideControlsTimeout);
        hideControlsTimeout = setTimeout(() => {
            if (!video.paused) {
                videoControls.classList.add('hidden');
            }
        }, 2000);
    }

    function handleResponsiveControls() {
        if (window.innerWidth <= 440) {
            setHideControlsTimeout();
        } else {
            videoControls.classList.remove('hidden');
            clearTimeout(hideControlsTimeout);
        }
    }

    function showControls() {
        videoControls.classList.remove('hidden');
        setHideControlsTimeout();
    }

    playPauseButton.addEventListener('click', function () {
        if (video.paused) {
            video.play();
            updateButtonImage(playPauseButton, pauseImageSrc);
        } else {
            video.pause();
            updateButtonImage(playPauseButton, playImageSrc);
        }
        showControls();
    });

    muteButton.addEventListener('click', function () {
        video.muted = !video.muted;
        updateButtonImage(muteButton, video.muted ? unmuteImageSrc : muteImageSrc);
    });

    video.addEventListener('click', handleResponsiveControls);
    window.addEventListener('resize', handleResponsiveControls);
});


document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.profession-card');
    let lastVisibleCard = null;
    let previousCards = [];

    const isElementInViewport = (element) => {
        const rect = element.getBoundingClientRect();
        return (
            rect.top + -100 >= 0 &&
            rect.bottom - 100 <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.left >= 0 &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    };

    const handleIntersection = () => {
        let firstVisibleCard = null;

        cards.forEach(card => {
            if (isElementInViewport(card)) {
                if (!firstVisibleCard) {
                    firstVisibleCard = card;
                }
            }

            if (!previousCards.includes(card)) {
                card.classList.remove('hover');
            }
        });

        if (firstVisibleCard && firstVisibleCard !== lastVisibleCard) {
            if (window.innerWidth < 500) {
                previousCards.push(firstVisibleCard);

                if (previousCards.length > 2) {
                    const oldestCard = previousCards.shift();
                    oldestCard.classList.remove('hover');
                }

                firstVisibleCard.classList.add('hover');
                lastVisibleCard = firstVisibleCard;
            }
        }
    };

    const observer = new IntersectionObserver((entries) => {
        handleIntersection();
    }, {
        threshold: [0, 0.1, 1.0]
    });

    cards.forEach(card => observer.observe(card));

    handleIntersection();

    window.addEventListener('resize', handleIntersection);
});

jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.steps-items');

        $slickSlider.slick({
            slidesPerRow: 6,
            rows: 1,
            swipe: false,

            nextArrow: false,
            prevArrow: false,
            responsive: [
                {
                    breakpoint: 1300,
                    settings: {
                        infinite: true,
                        swipe: true,
                        rows: 1,
                        slidesPerRow: 1,
                        slidesToScroll: 1, 
                        variableWidth: true,
                        swipeToSlide: true,
                        touchThreshold: 100, 
                        autoplay: true,
                        autoplaySpeed: 2000
                    }
                }
            ]
        });
    }

    initFirstSlider();
});

document.addEventListener('DOMContentLoaded', function() {
    const body = document.body;
    const popup = document.getElementById('thank-you-popup');
    let scrollPosition = 0;

    function lockScroll() {
        scrollPosition = window.pageYOffset || document.documentElement.scrollTop;
        body.style.position = 'fixed';
        body.style.top = `-${scrollPosition}px`;
        body.style.width = '100%';
    }

    function unlockScroll() {
        body.style.position = '';
        body.style.top = '';
        body.style.width = '';
        window.scrollTo(0, scrollPosition);
    }

    function closePopup() {
        unlockScroll();
        popup.style.display = 'none';
    }

    document.addEventListener('wpcf7mailsent', function() {
        lockScroll();
        popup.style.display = 'flex';
    }, false);

    document.querySelector('.close-popup').addEventListener('click', closePopup);

    popup.addEventListener('click', function(e) {
        if (e.target === popup) closePopup();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var firstImage = "/wp-content/uploads/2024/12/open-button.svg";
    var secondImage = "/wp-content/uploads/2024/12/close-button.svg";

    document.querySelectorAll('.faq-item h3').forEach(function(title) {
        title.addEventListener('click', function() {
            var faqItem = title.parentElement;
            var content = title.nextElementSibling;
            var image = title.querySelector('.toggle-image');
            var isActive = faqItem.classList.contains('show');

            document.querySelectorAll('.faq-item.show').forEach(function(openFaqItem) {
                openFaqItem.classList.remove('show');
                openFaqItem.querySelector('.toggle-image').src = firstImage;
                openFaqItem.querySelector('div').style.maxHeight = null;
            });

            if (!isActive) {
                faqItem.classList.add('show');
                image.src = secondImage;
                content.style.maxHeight = content.scrollHeight + 'px';
            } else {
                image.src = firstImage;
            }
        });
    });
});

jQuery(document).ready(function($) {
    function initSlider() {
        if (!$('.buyer-stories__cards').hasClass('slick-initialized2')) {
            $('.buyer-stories__cards').slick({
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
    $('.buyer-stories__cards').on('mouseover', function() {
        $(this).slick('slickPause');
    }).on('mouseout', function() {
        $(this).slick('slickPlay');
    });
});

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
        jQuery('.buyer-stories__cards').slick('slickPause');
    }, 100);
}

function closeVideoModal() {
    const modal = document.getElementById("videoModal");
    const videoFrame = document.getElementById("videoFrame");

    modal.style.display = "none";
    videoFrame.src = "";
    unlockScroll();
    jQuery('.buyer-stories__cards').slick('slickPlay');
}