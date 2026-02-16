<!-- Header -->
@php(
  $rawMenuItems = \App\Models\Menu::with('category')->active()->orderBy('order')->get()
)
@php(
  $allowedSlugs = config('sections.header_menu_slugs', [])
)
@php(
  $menuItems = ($rawMenuItems->isNotEmpty() ? $rawMenuItems : \App\Models\Category::where('show_in_menu', true)
    ->whereNull('parent_id')->orderBy('order')->get())
    ->map(function ($item) {
      $locale = app()->getLocale();
      if ($item instanceof \App\Models\Menu) {
        $label = $item->getTranslation('title', $locale) ?? ($item->category?->getTranslation('name', $locale));
        return [
          'label' => $label ?? '',
          'url' => $item->resolved_url,
          'is_external' => $item->target_type === 'external',
          'slug' => $item->section_slug ?? $item->category?->slug ?? \Illuminate\Support\Str::slug($label ?? ''),
        ];
      }

      $label = $item->getTranslation('link', $locale) ?: $item->getTranslation('name', $locale);
      $link = $item->link;

      return [
        'label' => $label,
        'url' => $link ? url($link) : route('home') . '/#' . $item->slug,
        'is_external' => filled($link) && \Illuminate\Support\Str::startsWith($link, ['http://', 'https://']),
        'slug' => $item->slug,
      ];
    })
    ->filter(function ($item) use ($allowedSlugs) {
      return empty($allowedSlugs) || in_array($item['slug'], $allowedSlugs, true);
    })
    ->sortBy(function ($item) use ($allowedSlugs) {
      $index = array_search($item['slug'], $allowedSlugs, true);
      return $index === false ? PHP_INT_MAX : $index;
    })
    ->values()
)
<header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16">
      <a href="/" class="flex items-center gap-2">
        <img src="{{ asset('assets/logo.svg') }}" alt="" width="100" class="block mx-auto lg:mx-0">

      </a>
      <nav class="hidden md:flex items-center gap-8 text-sm">
        @foreach($menuItems as $menuItem)
          <a href="{{ $menuItem['url'] }}" @if($menuItem['is_external']) target="_blank" rel="noopener" @endif
            class="hover:text-cyan-accent whitespace-nowrap px-2">{{ $menuItem['label'] }}</a>
        @endforeach
      </nav>
      <div class="flex items-center gap-3">
        <div class="hidden sm:inline-flex items-center rounded-lg border border-slate-200 overflow-hidden">
          <a href="{{ route('lang.switch', 'ar') }}"
            class="px-3 py-2 text-sm font-semibold {{ app()->getLocale() === 'ar' ? 'bg-primary-blue text-white' : 'text-gray-900 hover:bg-slate-50' }}">AR</a>
          <a href="{{ route('lang.switch', 'en') }}"
            class="px-3 py-2 text-sm font-semibold {{ app()->getLocale() === 'en' ? 'bg-primary-blue text-white' : 'text-gray-900 hover:bg-slate-50' }}">EN</a>
        </div>
        @guest
          <a href="{{ route('login') }}"
            class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">{{ __('navbar.login') }}</a>
          <a href="{{ route('register') }}"
            class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90">{{ __('navbar.register') }}</a>
        @else
          <form action="{{ route('logout') }}" method="POST" class="hidden sm:inline-flex">
            @csrf
            <button type="submit" class="px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">Logout</button>
          </form>
        @endguest
        @auth
          {{-- <a href="#contact"
            class="hidden sm:inline-flex items-center px-4 py-2 rounded-lg bg-primary-blue text-white font-semibold shadow-glow hover:opacity-90 transition">Get
            Free Session</a> --}}
        @endauth
        <button id="mobileMenuToggle"
          class="md:hidden inline-flex items-center justify-center w-10 h-10 rounded-lg border border-slate-200 hover:bg-slate-50"
          aria-label="Toggle menu">
          <i class="fi fi-rr-menu-burger w-5 h-5"></i>
        </button>
      </div>
    </div>
  </div>
  <!-- Mobile menu -->
  <div id="mobileMenu" class="md:hidden">
    <div class="menu-header">
      <img src="assets/logo-white.svg" alt="EradcHub" class="h-8 w-auto" />
      <button id="mobileMenuClose"
        class="inline-flex items-center justify-center w-12 h-12 rounded-lg text-white hover:text-white/80"
        aria-label="Close menu">
        <i class="fi fi-rr-cross-small w-6 h-6"></i>
      </button>
    </div>
    <div class="menu-content px-4 py-3 space-y-2">
      @foreach($menuItems as $menuItem)
        <a href="{{ $menuItem['url'] }}" @if($menuItem['is_external']) target="_blank" rel="noopener" @endif>
          <div class="flex items-center justify-between">
            <span>{{ $menuItem['label'] }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
              <path
                d="M9.47 4.47a.75.75 0 0 1 1.06 0l7 7a.75.75 0 0 1 0 1.06l-7 7a.75.75 0 1 1-1.06-1.06L15.94 12 9.47 5.53a.75.75 0 0 1 0-1.06Z" />
            </svg>
          </div>
        </a>
      @endforeach
    </div>
    <div class="menu-footer">
      @guest
        <a href="{{ route('login') }}" class="login-btn" aria-label="Login">{{ __('navbar.login') }}</a>
      @else
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="login-btn" aria-label="Logout">تسجيل الخروج</button>
        </form>
      @endguest
      <div class="social">
        <a href="#" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a>
        <a href="#" aria-label="Twitter"><i class="fi fi-brands-twitter-alt"></i></a>
        <a href="#" aria-label="Instagram"><i class="fi fi-brands-instagram"></i></a>
        <a href="#" aria-label="LinkedIn"><i class="fi fi-brands-linkedin"></i></a>
        <a href="#" aria-label="TikTok"><i class="fi fi-brands-tik-tok"></i></a>
      </div>
      <div class="social">
        <a href="#" aria-label="Facebook"><i class="fi fi-brands-facebook"></i></a>
        <a href="#" aria-label="Twitter"><i class="fi fi-brands-twitter-alt"></i></a>
        <a href="#" aria-label="Instagram"><i class="fi fi-brands-instagram"></i></a>
        <a href="#" aria-label="LinkedIn"><i class="fi fi-brands-linkedin"></i></a>
        <a href="#" aria-label="TikTok"><i class="fi fi-brands-tik-tok"></i></a>
      </div>
      <div class="copy">منصة مركز البيانات العصري | EradcHub 2025</div>
    </div>
  </div>
</header>