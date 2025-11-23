// Mobile menu toggle (slide-in from right)
const mobileToggle = document.getElementById('mobileMenuToggle');
const mobileMenu = document.getElementById('mobileMenu');
const headerEl = document.querySelector('header');

// Update CSS var for header height so menu fills viewport below it
function updateHeaderHeight() {
  if (!headerEl) return;
  const h = headerEl.offsetHeight;
  document.documentElement.style.setProperty('--header-h', h + 'px');
}
updateHeaderHeight();
window.addEventListener('resize', updateHeaderHeight);

if (mobileToggle && mobileMenu) {
  mobileToggle.addEventListener('click', () => {
    mobileMenu.classList.toggle('is-open');
    // Lock page scroll when menu open
    document.body.classList.toggle('overflow-hidden', mobileMenu.classList.contains('is-open'));
  });
  const mobileClose = document.getElementById('mobileMenuClose');
  if (mobileClose) {
    mobileClose.addEventListener('click', () => {
      mobileMenu.classList.remove('is-open');
      document.body.classList.remove('overflow-hidden');
    });
  }
  // Close menu when clicking a link
  mobileMenu.querySelectorAll('a').forEach(a => {
    a.addEventListener('click', () => {
      mobileMenu.classList.remove('is-open');
      document.body.classList.remove('overflow-hidden');
    });
  });

  // Auto-close mobile menu when leaving mobile viewport (>= md breakpoint)
  const MD_BREAKPOINT = 768; // Tailwind's default md breakpoint
  const autoCloseOnResize = () => {
    if (window.innerWidth >= MD_BREAKPOINT && mobileMenu.classList.contains('is-open')) {
      mobileMenu.classList.remove('is-open');
      document.body.classList.remove('overflow-hidden');
    }
  };
  window.addEventListener('resize', autoCloseOnResize);
  window.addEventListener('orientationchange', autoCloseOnResize);
  // Run once on load
  autoCloseOnResize();
}

// Sticky header shadow on scroll
const header = document.querySelector('header');
if (header) {
  const onScroll = () => {
    if (window.scrollY > 8) {
      header.classList.add('shadow');
    } else {
      header.classList.remove('shadow');
    }
  };
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
}

// FAQ accordion
document.querySelectorAll('.faq-item').forEach((item) => {
  const btn = item.querySelector('[data-accordion="toggle"]');
  const ans = item.querySelector('.faq-answer');
  if (!btn || !ans) return;
  btn.addEventListener('click', () => {
    ans.classList.toggle('hidden');
    btn.classList.toggle('text-brand-600');
    const icon = btn.querySelector('svg path');
    if (icon) {
      // rotate chevron by toggling class
      const svg = btn.querySelector('svg');
      svg.classList.toggle('rotate-180');
    }
  });
});

// Testimonials slider (Swiper)
(() => {
  const container = document.getElementById('testimonialsSwiper');
  if (!container) return;
  if (typeof Swiper === 'undefined') return;
  const swiper = new Swiper('#testimonialsSwiper', {
    slidesPerView: 1,
    spaceBetween: 16,
    loop: true,
    autoplay: { delay: 6000, disableOnInteraction: false },
    navigation: {
      prevEl: '#testPrev',
      nextEl: '#testNext',
    },
    pagination: {
      el: '#testPagination',
      clickable: true,
    },
    breakpoints: {
      640: { slidesPerView: 2 },
      1024: { slidesPerView: 3 },
    },
  });
})();

// Scroll-reveal animations for sections
(() => {
  const targets = document.querySelectorAll('section > .max-w-7xl');
  if (!targets.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      const el = entry.target;
      if (entry.isIntersecting) {
        el.classList.remove('opacity-0', 'translate-y-6');
        el.classList.add('animate-fade-in-up');
        observer.unobserve(el);
      }
    });
  }, { threshold: 0.15 });

  targets.forEach((el) => {
    el.classList.add('opacity-0', 'translate-y-6', 'will-change-transform');
    observer.observe(el);
  });
})();

// Dynamic year in footer
const yearEl = document.getElementById('year');
if (yearEl) {
  yearEl.textContent = new Date().getFullYear();
}

// Level selection: make radios behave like segmented buttons
(() => {
  const toggle = document.querySelector('.level-toggle');
  if (!toggle) return;
  const labels = Array.from(toggle.querySelectorAll('.option'));
  const update = () => {
    labels.forEach((l) => {
      const input = l.querySelector('input[type="radio"]');
      l.classList.toggle('selected', !!(input && input.checked));
    });
  };
  labels.forEach((l) => {
    const input = l.querySelector('input[type="radio"]');
    if (!input) return;
    input.addEventListener('change', update);
    l.addEventListener('click', () => {
      input.checked = true;
      input.dispatchEvent(new Event('change', { bubbles: true }));
    });
  });
  update();
})();

// Language (AR/EN) toggle with persistent setting; applies matching direction
// (() => {
//   const saved = localStorage.getItem('lang');
//   const urlLang = new URLSearchParams(window.location.search).get('lang');
//   const initial = (urlLang === 'ar' || urlLang === 'en') ? urlLang : (saved || 'ar');
//   document.documentElement.setAttribute('lang', initial);
//   document.documentElement.setAttribute('dir', initial === 'ar' ? 'rtl' : 'ltr');
//   localStorage.setItem('lang', initial);

//   const container = document.querySelector('header .flex.items-center.gap-3');
//   if (container) {
//     const btn = document.createElement('button');
//     btn.type = 'button';
//     btn.id = 'langToggle';
//     btn.className = 'hidden sm:inline-flex items-center px-3 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 text-sm';
//     const updateLabel = () => {
//       const current = document.documentElement.getAttribute('lang');
//       btn.textContent = current === 'ar' ? 'EN' : 'AR';
//     };
//     btn.addEventListener('click', () => {
//       const next = document.documentElement.getAttribute('lang') === 'ar' ? 'en' : 'ar';
//       document.documentElement.setAttribute('lang', next);
//       document.documentElement.setAttribute('dir', next === 'ar' ? 'rtl' : 'ltr');
//       localStorage.setItem('lang', next);
//       updateLabel();
//     });
//     updateLabel();
//     container.appendChild(btn);
//   }
// })();
