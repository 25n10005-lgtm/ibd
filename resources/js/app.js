// Scroll-reveal animations (respects prefers-reduced-motion)
document.addEventListener('DOMContentLoaded', () => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('[data-reveal]').forEach((el) => el.classList.add('is-visible'));

        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
    );

    document.querySelectorAll('[data-reveal]').forEach((el) => {
        if (el.dataset.delay) {
            el.style.transitionDelay = `${el.dataset.delay}ms`;
        }
        observer.observe(el);
    });

    // Navbar shadow on scroll
    const header = document.querySelector('[data-navbar]');
    if (header) {
        const toggle = () => header.classList.toggle('is-scrolled', window.scrollY > 8);
        toggle();
        window.addEventListener('scroll', toggle, { passive: true });
    }

    // Mobile menu
    const btn = document.querySelector('[data-menu-btn]');
    const menu = document.querySelector('[data-mobile-menu]');
    if (btn && menu) {
        btn.addEventListener('click', () => {
            const open = menu.classList.toggle('hidden');
            btn.setAttribute('aria-expanded', String(!open));
        });
        menu.querySelectorAll('a').forEach((a) => a.addEventListener('click', () => menu.classList.add('hidden')));
    }
});
