document.addEventListener('DOMContentLoaded', function() {
    let isVisible = false;
    const button = document.getElementById('backToTop');

    if (!button) {
        console.error('Back to top button not found');
        return;
    }

    // Set initial display style
    button.style.display = 'flex';

    function toggleButtonVisibility(show) {
        if (show === isVisible) return;
        isVisible = show;
        button.classList.toggle('visible', show);
    }

    function handleScroll() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        requestAnimationFrame(() => {
            toggleButtonVisibility(scrollTop > 100);
        });
    }

    function scrollToTop(e) {
        e.preventDefault();
        const duration = 500;
        const start = window.pageYOffset || document.documentElement.scrollTop;
        const startTime = performance.now();

        function animate(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Easing function
            const easing = t => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1;
            
            window.scrollTo(0, start * (1 - easing(progress)));

            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }

        requestAnimationFrame(animate);
    }

    // Throttle scroll events
    let scrollTimeout;
    window.addEventListener('scroll', () => {
        if (!scrollTimeout) {
            scrollTimeout = setTimeout(() => {
                handleScroll();
                scrollTimeout = null;
            }, 100);
        }
    }, { passive: true });

    button.addEventListener('click', scrollToTop);

    // Initial check
    handleScroll();
});