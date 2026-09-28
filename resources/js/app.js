import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('gallery', (slides = []) => ({
    slides,
    index: 0,
    isOpen: false,
    fading: false,
    opener: null,
    touchX: null,

    get current() {
        return this.slides[this.index] || { src: '', title: '', alt: '', chapter: '' };
    },

    pad(value) {
        return String(value).padStart(2, '0');
    },

    open(index, opener = null) {
        if (!this.slides.length) {
            return;
        }
        this.opener = opener;
        this.index = index;
        this.fading = false;
        this.isOpen = true;
        document.documentElement.style.overflow = 'hidden';
        this.preload();
        this.$nextTick(() => {
            this.$refs.close?.focus({ preventScroll: true });
            this.scrollThumb('auto');
        });
    },

    close() {
        this.isOpen = false;
        document.documentElement.style.overflow = '';
        this.opener?.focus({ preventScroll: true });
    },

    go(index) {
        const count = this.slides.length;
        const target = ((index % count) + count) % count;
        if (target === this.index) {
            return;
        }
        this.fading = true;
        setTimeout(() => {
            this.index = target;
            this.preload();
            this.scrollThumb('smooth');
        }, 180);
    },

    next() {
        this.go(this.index + 1);
    },

    prev() {
        this.go(this.index - 1);
    },

    preload() {
        [this.index + 1, this.index - 1].forEach((i) => {
            const slide = this.slides[(i + this.slides.length) % this.slides.length];
            if (slide) {
                const image = new Image();
                image.sizes = window.innerWidth >= 768 ? 'calc(100vw - 14rem)' : '100vw';
                image.srcset = slide.srcset || '';
                image.src = slide.src;
            }
        });
    },

    scrollThumb(behavior) {
        this.$nextTick(() => {
            const strip = this.$refs.thumbs;
            const thumb = strip?.querySelector('.is-active');
            if (!thumb) {
                return;
            }
            const offset = thumb.getBoundingClientRect().left - strip.getBoundingClientRect().left;
            strip.scrollTo({ left: strip.scrollLeft + offset - (strip.clientWidth - thumb.offsetWidth) / 2, behavior });
        });
    },

    onKey(event) {
        if (!this.isOpen) {
            return;
        }
        if (event.key === 'Escape') {
            this.close();
        } else if (event.key === 'ArrowRight') {
            this.next();
        } else if (event.key === 'ArrowLeft') {
            this.prev();
        } else if (event.key === 'Tab') {
            const focusable = [...this.$refs.dialog.querySelectorAll('button')].filter((el) => el.offsetParent !== null);
            const first = focusable[0];
            const last = focusable[focusable.length - 1];
            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    },

    touchStart(event) {
        this.touchX = event.changedTouches[0].clientX;
    },

    touchEnd(event) {
        if (this.touchX === null) {
            return;
        }
        const delta = event.changedTouches[0].clientX - this.touchX;
        this.touchX = null;
        if (Math.abs(delta) > 50) {
            delta < 0 ? this.next() : this.prev();
        }
    },
}));

Alpine.start();

const reveal = () => {
    const nodes = document.querySelectorAll('[data-reveal], [data-inview]');
    if (!nodes.length) {
        return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        nodes.forEach((node) => node.classList.add('is-in'));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }
            entry.target.classList.add('is-in');
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.16, rootMargin: '0px 0px -8% 0px' });

    nodes.forEach((node) => observer.observe(node));
};

const heroFilm = () => {
    const film = document.querySelector('[data-hero-film]');
    if (!film) {
        return;
    }

    const connection = navigator.connection;
    const constrained = connection && (connection.saveData || /(^|-)2g$/.test(connection.effectiveType || ''));

    if (constrained || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    const start = () => {
        const mobile = window.matchMedia('(max-width: 767px)').matches;
        film.src = (mobile && film.dataset.srcMobile) || film.dataset.src;
        film.addEventListener('playing', () => film.classList.add('is-playing'), { once: true });
        film.play().catch(() => {});

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                film.pause();
                return;
            }
            film.play().catch(() => {});
        });
    };

    const whenIdle = () => ('requestIdleCallback' in window
        ? window.requestIdleCallback(start, { timeout: 2500 })
        : window.setTimeout(start, 800));

    if (document.readyState === 'complete') {
        whenIdle();
    } else {
        window.addEventListener('load', whenIdle, { once: true });
    }
};

const navSpy = () => {
    const links = [...document.querySelectorAll('[data-spy]')];
    const sectionsFor = (link) => link.dataset.spy.split(',');
    const sections = [...new Set(links.flatMap(sectionsFor))]
        .map((id) => document.getElementById(id))
        .filter(Boolean)
        .sort((a, b) => (a.compareDocumentPosition(b) & Node.DOCUMENT_POSITION_FOLLOWING ? -1 : 1));

    if (!sections.length) {
        return;
    }

    let current;
    const update = () => {
        const header = document.querySelector('body > header');
        const line = (header?.getBoundingClientRect().bottom || 0) + 24;
        // Keep the preceding section selected through gaps and the closing content.
        // The first menu item also represents the introduction above its section.
        let hit = sections[0];
        for (const section of sections) {
            if (section.getBoundingClientRect().top > line) {
                break;
            }
            hit = section;
        }
        const id = hit.id;

        if (id === current) {
            return;
        }
        current = id;

        links.forEach((link) => {
            const active = id !== null && sectionsFor(link).includes(id);
            link.classList.toggle('is-active', active);
            if (active) {
                link.setAttribute('aria-current', 'location');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    };

    let queued = false;
    const schedule = () => {
        if (queued) {
            return;
        }
        queued = true;
        window.requestAnimationFrame(() => {
            queued = false;
            update();
        });
    };

    window.addEventListener('scroll', schedule, { passive: true });
    window.addEventListener('resize', schedule);
    window.addEventListener('pageshow', schedule);
    window.addEventListener('load', schedule, { once: true });
    if ('ResizeObserver' in window) {
        new ResizeObserver(schedule).observe(document.querySelector('main') || document.body);
    }
    update();
};

document.addEventListener('DOMContentLoaded', () => {
    reveal();
    heroFilm();
    navSpy();
});
