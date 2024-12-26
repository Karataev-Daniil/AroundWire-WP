/* connecting scripts for benefits blocks on landings */

/* common scripts for benefits blocks */

/* benefits V1 */
jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.benefits__slider.v1');

        $slickSlider.slick({
            infinite: false,
            swipe: true,
            rows: 1,
            slidesPerRow: 1,
            slidesToScroll: 1, 
            variableWidth: true,
            swipeToSlide: true,
            touchThreshold: 500, 
            nextArrow: false,
            prevArrow: false,
        });
    }

    initFirstSlider();
});

/* benefits V2 */
jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.benefits__slider.v2');

        $slickSlider.slick({
            slidesPerRow: 3,
            rows: 2,
            nextArrow: false,
            prevArrow: false,
            ariahidden: false,
            swipe: false,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        infinite: false,
                        swipe: true,
                        rows: 1,
                        slidesPerRow: 1,
                        slidesToScroll: 1, 
                        variableWidth: true,
                        swipeToSlide: true,
                        touchThreshold: 500, 
                    }
                }
            ]
        });
    }

    initFirstSlider();
});

/* benefits V3 */
jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.benefits__slider.v3');

        $slickSlider.slick({
            slidesPerRow: 3,
            rows: 2,
            swipe: false,
            nextArrow: false,
            prevArrow: false,
            ariahidden: false,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        infinite: false,
                        swipe: true,
                        rows: 1,
                        slidesPerRow: 1,
                        slidesToScroll: 1, 
                        variableWidth: true,
                        swipeToSlide: true,
                        touchThreshold: 500, 
                    }
                }
            ]
        });
    }

    initFirstSlider();
});

/* benefits V4 */
jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.benefits__slider.v4');

        $slickSlider.slick({
            slidesPerRow: 3,
            rows: 2,
            swipe: false,
            nextArrow: false,
            prevArrow: false,
            ariahidden: false,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        infinite: false,
                        swipe: true,
                        rows: 1,
                        slidesPerRow: 1,
                        slidesToScroll: 1, 
                        variableWidth: true,
                        swipeToSlide: true,
                        touchThreshold: 500, 
                    }
                }
            ]
        });
    }

    initFirstSlider();
});

/* benefits V5 */
jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.benefits__slider.v5');

        $slickSlider.slick({
            slidesPerRow: 3,
            rows: 2,
            swipe: false,
            nextArrow: false,
            prevArrow: false,
            ariahidden: false,
            responsive: [
                {
                    breakpoint: 769,
                    settings: {
                        slidesPerRow: 2,
                        rows: 3,
                    }
                },
                {
                    breakpoint: 650,
                    settings: {
                        infinite: false,
                        swipe: true,
                        rows: 1,
                        slidesPerRow: 1,
                        slidesToScroll: 1, 
                        variableWidth: true,
                        swipeToSlide: true,
                        touchThreshold: 500, 
                    }
                }
            ]
        });
    }

    initFirstSlider();
});

/* benefits V6 */
jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.benefits__slider.v6');

        $slickSlider.slick({
            slidesPerRow: 3,
            rows: 2,
            swipe: false,
            nextArrow: false,
            prevArrow: false,
            ariahidden: false,
            responsive: [
                {
                    breakpoint: 769,
                    settings: {
                        slidesPerRow: 2,
                        rows: 3,
                    }
                },
                {
                    breakpoint: 650,
                    settings: {
                        infinite: false,
                        swipe: true,
                        rows: 1,
                        slidesPerRow: 1,
                        slidesToScroll: 1, 
                        variableWidth: true,
                        swipeToSlide: true,
                        touchThreshold: 500, 
                    }
                }
            ]
        });
    }

    initFirstSlider();
});

/* benefits V7 */
jQuery(document).ready(function($) {
    function initFirstSlider() {
        var $slickSlider = $('.benefits__slider.v7');

        $slickSlider.slick({
            slidesPerRow: 3,
            rows: 2,
            swipe: true,
            nextArrow: false,
            prevArrow: false,
            ariahidden: false,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        infinite: false,
                        swipe: true,
                        rows: 1,
                        slidesPerRow: 1,
                        slidesToScroll: 1, 
                        variableWidth: true,
                        swipeToSlide: true,
                        touchThreshold: 500, 
                    }
                }
            ]
        });
    }

    initFirstSlider();
});