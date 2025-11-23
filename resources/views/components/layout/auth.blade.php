<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register – EradcHub</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}" />
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-regular-rounded/css/uicons-regular-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-bold-rounded/css/uicons-bold-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-solid-rounded/css/uicons-solid-rounded.css">
    <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/2.6.0/uicons-brands/css/uicons-brands.css">

    <style>
      section#mission-vision, section#core-values, section#strategic-partners, section#services, section#contact, section#courses, section#image-bg-section، section#trainers { display: block !important; }

      /* Services Accordion RTL styles */
      .services-accordion details {
        border: 1px solid #e2e8f0; /* slate-200 */
        border-radius: 1rem; /* rounded-2xl */
        background: #fff;
        padding: 0.75rem 1rem;
        transition: box-shadow 200ms ease, border-color 200ms ease;
      }
      .services-accordion details + details { margin-top: 0.75rem; }
      .services-accordion summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        list-style: none;
      }
      .services-accordion summary::-webkit-details-marker { display: none; }
      .services-accordion .title {
        font-weight: 600;
        color: #0f172a; /* slate-900 */
      }
      .services-accordion .content {
        margin-top: 0.75rem;
        color: #334155; /* slate-700 */
        font-size: 0.95rem;
      }
      .services-accordion .chevron                 { transition: transform 200ms ease; }
      .services-accordion details[open] .chevron { transform: rotate(180deg); }
      .services-accordion details:hover { box-shadow: 0 8px 24px rgba(16, 24, 40, 0.06); border-color: #cbd5e1; }
      .services-accordion summary:focus-visible { outline: 2px solid #06b6d4; outline-offset: 3px; }

      /* Override: summary white/black, content blue/white, no borders/shadows */
      .services-accordion details { border: none; border-radius: 1rem; background: #ffffff; color: #0f172a; padding: 0.75rem 1rem; }
      .services-accordion details:hover { box-shadow: none; border-color: transparent; }
      .services-accordion .title { color: #0f172a; }
      .services-accordion summary { background: #ffffff; border-bottom: 2px solid #000; padding-bottom: 0.5rem; }
      .services-accordion summary .chevron-plus, .services-accordion summary .chevron-minus { color: #0f172a !important; }
      .services-accordion .content { margin-top: 0.75rem; background: #2e3192; color: #ffffff; font-size: 0.95rem; padding: 0.75rem 1rem; border-radius: 0.75rem; }
      .services-accordion .chevron-plus { display: inline-block; }
      .services-accordion .chevron-minus { display: none; }
      .services-accordion details[open] .chevron-plus { display: none; }
      .services-accordion details[open] .chevron-minus { display: inline-block; }
      .services-accordion .action-btn { display: inline-block; margin-top: 0.75rem; padding: 0.5rem 0.9rem; background: #ffffff; color: #2e3192; font-weight: 600; border-radius: 0.5rem; }

      /* Responsive alignment for hero action and metrics rows */
      @media (min-width: 768px) { .hero-actions { justify-content: flex-start !important; } .hero-metrics { justify-content: flex-start !important; } }
      .services-accordion ul li span:first-child { display: inline-block; margin-left: 0.5rem; }
      @media (prefers-reduced-motion: reduce) { .services-accordion .chevron { transition: none; } }

      /* Courses image tile grid styles */
      #courses .course-tile { box-shadow: 0 8px 24px rgba(16, 24, 40, 0.06); }
      #courses .course-tile:hover { box-shadow: 0 16px 32px rgba(16, 24, 40, 0.10); }
      #courses .course-tile img { display:block; }
      @media (prefers-reduced-motion: reduce) { #courses .course-tile img { transition: none; } }

      .swiper { width: 100%; height: 100%; }
      .copy-section { order: 1; }
      .relative { order: 2; }
      @media (max-width: 989px) { .copy-section { text-align: center; order: 2; } .relative { text-align: center; order: 1; } }
      .swiper-slide { text-align: center; font-size: 18px; background: transparent; display: flex; justify-content: center; align-items: center; }
      .swiper-slide img { display: block; width: 100%; height: 100%; object-fit: cover; }

      /* Mobile menu overlay: full-screen with internal header */
      :root { --header-h: 64px; }
      header { position: relative; }
      #mobileMenu { position: fixed; top: 0; right: 0; left: 0; bottom: 0; height: 100vh; background: #2e3192; z-index: 60; opacity: 0; visibility: hidden; pointer-events: none; transform: translateY(-10px); transition: transform 200ms ease, opacity 200ms ease; display: flex; flex-direction: column; overflow-y: hidden; }
      #mobileMenu.is-open { transform: translateY(0); opacity: 1; visibility: visible; pointer-events: auto; }
      #mobileMenu .menu-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem; color: #fff; }
      #mobileMenu .menu-header #mobileMenuClose { width: 48px; height: 48px; border: 1.5px solid rgba(255,255,255,0.85); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; }
      #mobileMenu .menu-header #mobileMenuClose i { font-size: 20px; }
      #mobileMenu .menu-content { max-width: 640px; margin-inline: auto; padding: 1rem; flex: 1; overflow-y: auto; }
      #mobileMenu a { display: block; padding: 0.75rem 1rem; margin-block: 0.25rem; background: #ffffff; color: #2e3192; border-radius: 0.75rem; font-weight: 600; }
      #mobileMenu a:hover { opacity: 0.9; }
      #mobileMenu .menu-footer { max-width: 640px; margin-inline: auto; padding: 1rem; border-top: 1px solid rgba(255,255,255,0.2); }
      #mobileMenu .menu-footer .social { display: flex; align-items: center; justify-content: center; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1rem; }
      #mobileMenu .menu-footer .social a { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.7); border-radius: 9999px; color: #ffffff; }
      #mobileMenu .menu-footer .social a:hover { background: rgba(255,255,255,0.1); }
      #mobileMenu .menu-footer .login-btn { display: block; width: 100%; max-width: 640px; margin: 0 auto 0.75rem; padding: 0.75rem 1rem; background: #ffffff; color: #2e3192; font-weight: 600; border-radius: 0.75rem; text-align: center; }
      #mobileMenu .menu-footer .copy { margin-top: 0.75rem; text-align: center; color: rgba(255,255,255,0.9); font-size: 0.85rem; }

      /* Logo marquee using Swiper with edge fades */
      #logo-marquee .logo-swiper { position: relative; overflow: hidden; -webkit-mask-image: linear-gradient(to right, transparent 0, black 60px, black calc(100% - 60px), transparent 100%); mask-image: linear-gradient(to right, transparent 0, black 60px, black calc(100% - 60px), transparent 100%); }
      #logo-marquee .logo-swiper .swiper-wrapper { align-items: center; }
      #logo-marquee .logo-swiper .swiper-slide { width: auto; }
      #logo-marquee .logo-swiper img { display: block; width: 100px; height: auto; }

      /* Levels section: full-width dark blue border around summary */
      #levels .services-accordion details { padding: 0; }
      #levels .services-accordion summary { width: 100%; border: 2px solid #2e3192; border-radius: 0.75rem; padding: 0.75rem 1rem; background: #ffffff; }
      #levels .services-accordion .title { display: block; width: 100%; border: none; padding-bottom: 0; }

      /* Infra Projects: grid of logos (5 per row; 3 on small screens) */
      #infra-projects .logo-grid { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1rem; align-items: center; }
      #infra-projects .logo-item { display: flex; align-items: center; justify-content: center; padding: 1rem; border: 1px solid #e2e8f0; border-radius: 0.75rem; background: #ffffff; }
      #infra-projects .logo-item img { width: 100px; height: auto; display: block; }
      @media (max-width: 640px) { #infra-projects .logo-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }


    <style>
      /* Hide all sections except the first one (Hero), but show Mission & Vision and Core Values */
      /* section:not(:first-of-type) { display: none !important; } */
      section#mission-vision, section#core-values, section#strategic-partners, section#services, section#contact, section#courses, section#image-bg-section، section#trainers { display: block !important; }

      /* Services Accordion RTL styles */
      .services-accordion details {
        border: 1px solid #e2e8f0; /* slate-200 */
        border-radius: 1rem; /* rounded-2xl */
        background: #fff;
        padding: 0.75rem 1rem;
        transition: box-shadow 200ms ease, border-color 200ms ease;
      }
      .services-accordion details + details { margin-top: 0.75rem; }
      .services-accordion summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        list-style: none;
      }
      .services-accordion summary::-webkit-details-marker { display: none; }
      .services-accordion .title {
        font-weight: 600;
        color: #0f172a; /* slate-900 */
      }
      .services-accordion .content {
        margin-top: 0.75rem;
        color: #334155; /* slate-700 */
        font-size: 0.95rem;
      }
      .services-accordion .chevron                 { transition: transform 200ms ease; }
      .services-accordion details[open] .chevron { transform: rotate(180deg); }
      .services-accordion details:hover { box-shadow: 0 8px 24px rgba(16, 24, 40, 0.06); border-color: #cbd5e1; }
      .services-accordion summary:focus-visible { outline: 2px solid #06b6d4; outline-offset: 3px; }

      /* Override: summary white/black, content blue/white, no borders/shadows */
      .services-accordion details {
        border: none;
        border-radius: 1rem; /* keep rounded look without border */
        background: #ffffff; /* summary area background */
        color: #0f172a; /* default text color for summary */
        padding: 0.75rem 1rem;
      }
      .services-accordion details:hover {
        box-shadow: none;
        border-color: transparent;
      }
      .services-accordion .title {
        color: #0f172a;
      }
      .services-accordion summary {
        background: #ffffff;
        border-bottom: 2px solid #000; /* black underline spanning the row */
        padding-bottom: 0.5rem;
      }
      .services-accordion summary .chevron-plus,
      .services-accordion summary .chevron-minus { color: #0f172a !important; }
      .services-accordion .content {
        margin-top: 0.75rem;
        background: #2e3192; /* dark blue from design */
        color: #ffffff;
        font-size: 0.95rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
      }
      /* Plus/Minus toggle for summary icons */
      .services-accordion .chevron-plus { display: inline-block; }
      .services-accordion .chevron-minus { display: none; }
      .services-accordion details[open] .chevron-plus { display: none; }
      .services-accordion details[open] .chevron-minus { display: inline-block; }
      /* Action button inside content */
      .services-accordion .action-btn {
        display: inline-block;
        margin-top: 0.75rem;
        padding: 0.5rem 0.9rem;
        background: #ffffff;
        color: #2e3192;
        font-weight: 600;
        border-radius: 0.5rem;
      }

      /* Responsive alignment for hero action and metrics rows */
      @media (min-width: 768px) {
        .hero-actions { justify-content: flex-start !important; }
        .hero-metrics { justify-content: flex-start !important; }
      }
      .services-accordion ul li span:first-child { display: inline-block; margin-left: 0.5rem; }
      @media (prefers-reduced-motion: reduce) { .services-accordion .chevron { transition: none; } }

      /* Courses image tile grid styles */
       #courses .course-tile { box-shadow: 0 8px 24px rgba(16, 24, 40, 0.06); }
       #courses .course-tile:hover { box-shadow: 0 16px 32px rgba(16, 24, 40, 0.10); }
       #courses .course-tile img { display:block; }
       @media (prefers-reduced-motion: reduce) { #courses .course-tile img { transition: none; } }

    .swiper {
      width: 70vw;
      height: 100%;
    }

    .copy-section {
        order: 1;
      }

      .relative {
        order: 2;
      }

    @media (max-width: 989px) {
      .copy-section {
        text-align: center;
        order: 2;
      }
      .relative {
        text-align: center;
        order: 1;
      }
    }

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: transparent;
      display: flex;
      justify-content: center;
      align-items: center;
    }

      .swiper-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      /* Mobile menu dropdown under header (full height below header) */
      :root { --header-h: 64px; } /* fallback; JS updates this value */
      header { position: relative; }
      #mobileMenu {
        position: fixed;
        top: var(--header-h);
        right: 0;
        left: 0;
        bottom: 0;
        height: calc(100vh - var(--header-h));
        background: #2e3192; /* solid dark blue background */
        z-index: 60; /* above page content */
        overflow-y: auto;
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transform: translateY(-10px);
        transition: transform 200ms ease, opacity 200ms ease;
      }
      #mobileMenu.is-open {
        transform: translateY(0);
        opacity: 1;
        visibility: visible;
        pointer-events: auto;
        width: calc(90vw)
      }
      /* Constrain content width so menu is not full-screen width */
      #mobileMenu .menu-content {
        max-width: 640px;
        margin-inline: 1rem;
      }
      #mobileMenu .menu-content { padding: 1rem; }
      #mobileMenu a {
        display: block;
        padding: 0.75rem 1rem;
        margin-block: 0.25rem;
        background: #ffffff; /* white rectangles */
        color: #2e3192; /* blue text */
        border-radius: 0.75rem;
        font-weight: 600;
      }
      #mobileMenu a:hover { opacity: 0.9; }

      /* Logo marquee using Swiper with edge fades */
      #logo-marquee .logo-swiper {
        position: relative;
        overflow: hidden;
        -webkit-mask-image: linear-gradient(to right, transparent 0, black 60px, black calc(100% - 60px), transparent 100%);
                mask-image: linear-gradient(to right, transparent 0, black 60px, black calc(100% - 60px), transparent 100%);
      }
      #logo-marquee .logo-swiper .swiper-wrapper { align-items: center; }
      #logo-marquee .logo-swiper .swiper-slide { width: auto; }
      #logo-marquee .logo-swiper img {
        display: block;
        width: 180px;
        height: auto;
      }

      /* Levels section: full-width dark blue border around summary */
      #levels .services-accordion details { padding: 0; }
      #levels .services-accordion summary {
        width: 100%;
        border: 2px solid #2e3192; /* dark blue */
        border-radius: 0.75rem;
        padding: 0.75rem 1rem; /* internal padding inside bordered box */
        background: #ffffff;
      }
      #levels .services-accordion .title {
        display: block;
        width: 100%;
        border: none; /* no underline; border is on the summary */
        padding-bottom: 0;
      }

      /* Infra Projects: grid of logos (5 per row) */
      #infra-projects .logo-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 1rem;
        align-items: center;
      }
      #infra-projects .logo-item {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        border: 1px solid #e2e8f0; /* slate-200 */
        border-radius: 0.75rem; /* rounded-xl */
        background: #ffffff;
      }
      #infra-projects .logo-item img {
        width: 100px;
        height: auto;
        display: block;
      }
      @media (max-width: 640px) {
        #infra-projects .logo-grid {
          grid-template-columns: repeat(3, minmax(0, 1fr));
        }
      }

      /* Mobile menu overlay: full-screen with internal header */
      :root { --header-h: 64px; }
      header { position: relative; }
      #mobileMenu { position: fixed; top: 0; right: 0; left: 0; bottom: 0; height: 100vh; background: #2e3192; z-index: 60; opacity: 0; visibility: hidden; pointer-events: none; transform: translateY(-10px); transition: transform 200ms ease, opacity 200ms ease; display: flex; flex-direction: column; overflow-y: hidden; }
      #mobileMenu.is-open { transform: translateY(0); opacity: 1; visibility: visible; pointer-events: auto; width: calc(85vw); }
      #mobileMenu .menu-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem; color: #fff; }
      #mobileMenu .menu-header #mobileMenuClose { width: 48px; height: 48px; border: 1.5px solid rgba(255,255,255,0.85); border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; }
      #mobileMenu .menu-header #mobileMenuClose i { font-size: 20px; }
      #mobileMenu .menu-content { max-width: 640px; margin-inline: 1rem; padding: 1rem; flex: 1; overflow-y: auto; }
      #mobileMenu a { display: block; padding: 0.75rem 1rem; margin-block: 0.25rem; background: #ffffff; color: #2e3192; border-radius: 0.75rem; font-weight: 600; }
      #mobileMenu a:hover { opacity: 0.9; }
      #mobileMenu .menu-footer { max-width: 640px; margin-inline: auto; padding: 1rem;  }
      #mobileMenu .menu-footer .social { display: flex; align-items: center; justify-content: center; gap: 0.75rem; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 1rem }
      #mobileMenu .menu-footer .social a { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.7); border-radius: 9999px; color: #ffffff; }
      #mobileMenu .menu-footer .social a { background: rgba(255,255,255,0.1); }
      #mobileMenu .menu-footer .copy { margin-top: 0.5rem; text-align: center; color: rgba(255,255,255,0.9); font-size: 0.85rem; }
       #mobileMenu .menu-footer .social a i {
          margin-top: 4px
       }
      #mobileMenu .menu-footer .login-btn { text-align: center; margin-bottom: 0.75rem; }

      /* Level selection toggle buttons */
      .level-toggle { display: flex; gap: 0.5rem; }
      .level-toggle .option {
        position: relative;
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        border: 1px solid #2e3192;
        color: #2e3192;
        background: #ffffff;
        font-weight: 600;
        cursor: pointer;
      }
      .level-toggle .option input { position: absolute; inset: 0; opacity: 0; pointer-events: none; }
      .level-toggle .option.selected { background: #2e3192; color: #ffffff; }
      @media (prefers-reduced-motion: reduce) { .level-toggle .option { transition: none; } }

    </style>

  </head>
  <body class="bg-gradient-to-br from-white to-primary-blue text-neutral-dark antialiased overflow-x-hidden min-h-screen" style="background-image: linear-gradient(to bottom right, #ffffff 0%, #f3f8ff 40%, #93c5fd 100%); min-height: 100vh; display: flex; flex-direction: column;">
    <x-include.header />

        {{ $slot }}

    <x-include.footer />
  <script src="{{ asset('assets/main.js') }}"></script>
  </body>
  </html>
