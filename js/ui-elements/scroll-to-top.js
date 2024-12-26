document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('scrollToTopBtn');
    const arrow = btn.querySelector('.arrow');
    let isScrollToTopVisible = false;
    const threshold = 20;

    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function handleScroll() {
        const shouldShow = document.documentElement.scrollTop > threshold;

        if (shouldShow !== isScrollToTopVisible) {
            if (!shouldShow) {
                btn.classList.remove('animate');
            }
            btn.style.display = shouldShow ? 'block' : 'none';
            isScrollToTopVisible = shouldShow;
        }
    }

    btn.addEventListener('mouseenter', () => {
        btn.classList.add('animate');
    });

    btn.addEventListener('animationend', () => {
        if (document.documentElement.scrollTop > threshold) {
            btn.classList.remove('animate');
        }
    });

    btn.addEventListener('click', () => {
        btn.classList.add('animate');
        scrollToTop();
    });

    window.addEventListener('scroll', () => {
        requestAnimationFrame(handleScroll);
    });

    handleScroll();
});
