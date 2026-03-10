// Landing Page Carousel
import '../css/app.css';

document.addEventListener('DOMContentLoaded', function () {
    const carousel = document.getElementById('hero-carousel');
    if (!carousel) return;

    const slides = carousel.querySelectorAll('.carousel-slide');
    const prevBtn = carousel.querySelector('.carousel-prev');
    const nextBtn = carousel.querySelector('.carousel-next');
    const dotsContainer = carousel.querySelector('.carousel-dots');
    
    let currentSlide = 0;
    let autoplayInterval;
    let isPaused = false;

    // Create dots
    slides.forEach((_, index) => {
        const dot = document.createElement('button');
        dot.className = `w-3 h-3 rounded-full transition-colors ${index === 0 ? 'bg-green-500' : 'bg-gray-500 hover:bg-gray-400'}`;
        dot.setAttribute('aria-label', `Go to slide ${index + 1}`);
        dot.addEventListener('click', () => goToSlide(index));
        dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('button');

    function updateSlides() {
        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === currentSlide);
        });
        dots.forEach((dot, index) => {
            dot.className = `w-3 h-3 rounded-full transition-colors ${index === currentSlide ? 'bg-green-500' : 'bg-gray-500 hover:bg-gray-400'}`;
        });
    }

    function goToSlide(index) {
        currentSlide = index;
        updateSlides();
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        updateSlides();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        updateSlides();
    }

    // Event listeners
    nextBtn?.addEventListener('click', () => {
        nextSlide();
        resetAutoplay();
    });

    prevBtn?.addEventListener('click', () => {
        prevSlide();
        resetAutoplay();
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') {
            prevSlide();
            resetAutoplay();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            resetAutoplay();
        }
    });

    // Autoplay
    function startAutoplay() {
        autoplayInterval = setInterval(() => {
            if (!isPaused) {
                nextSlide();
            }
        }, 5000);
    }

    function resetAutoplay() {
        clearInterval(autoplayInterval);
        startAutoplay();
    }

    // Pause on hover
    carousel.addEventListener('mouseenter', () => {
        isPaused = true;
    });

    carousel.addEventListener('mouseleave', () => {
        isPaused = false;
    });

    // Touch/swipe support
    let touchStartX = 0;
    let touchEndX = 0;

    carousel.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    carousel.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    function handleSwipe() {
        const swipeThreshold = 50;
        const diff = touchStartX - touchEndX;

        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
            resetAutoplay();
        }
    }

    // Initialize
    startAutoplay();
});