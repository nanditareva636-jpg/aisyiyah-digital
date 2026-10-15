/**
 * Aisyiyah Digital Jawa Tengah - Custom JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
    // 1. Efek bayangan navbar saat halaman di-scroll
    const navbar = document.querySelector('.navbar-custom');
    if (navbar) {
        window.addEventListener('scroll', function () {
            if (window.scrollY > 40) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }

    // 2. Animasi sederhana untuk angka statistik
    const statNumbers = document.querySelectorAll('.stat-number');
    let animated = false;

    function animateStats() {
        if (animated) return;
        statNumbers.forEach(stat => {
            const text = stat.innerText;
            const target = parseInt(text.replace(/[^0-9]/g, ''));
            if (isNaN(target)) return;

            let count = 0;
            const duration = 1200; // ms
            const stepTime = 30;
            const stepValue = Math.ceil(target / (duration / stepTime));

            const timer = setInterval(() => {
                count += stepValue;
                if (count >= target) {
                    count = target;
                    clearInterval(timer);
                    stat.innerText = text; // Kembalikan teks asli beserta simbol + atau lainnya
                } else {
                    stat.innerText = count + (text.includes('+') ? '+' : '');
                }
            }, stepTime);
        });
        animated = true;
    }

    // Jalankan animasi statistik saat bagian statistik terlihat
    const statSection = document.querySelector('.section-stats');
    if (statSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateStats();
                }
            });
        }, { threshold: 0.3 });
        observer.observe(statSection);
    }
});
