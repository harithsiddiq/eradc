<x-layout.auth>
  <main class="py-8 sm:py-12" style="display:flex; align-items:center; justify-content:center; min-height: 60vh;">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="rounded-2xl p-8 bg-white shadow-sm border border-slate-100">
        <div class="mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-primary-blue" style="color: #2e3192;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.136 0l-7.5-4.615a2.25 2.25 0 01-1.07-1.916V6.75" />
          </svg>
        </div>

        <div class="flex items-center justify-end gap-2 text-sm text-slate-600 mb-4">
          <a href="{{ route('lang.switch', 'ar') }}"
            class="hover:text-primary-blue {{ app()->getLocale() === 'ar' ? 'font-semibold text-primary-blue' : '' }}">العربية</a>
          <span class="text-slate-400">/</span>
          <a href="{{ route('lang.switch', 'en') }}"
            class="hover:text-primary-blue {{ app()->getLocale() === 'en' ? 'font-semibold text-primary-blue' : '' }}">English</a>
        </div>

        <h1 class="text-2xl font-bold mb-4">{{ __('auth.verify.title') }}</h1>

        <p class="text-slate-700 mb-6">{{ __('auth.verify.description') }}</p>

        @if (session('status') === 'verification-link-sent')
          <div class="mb-4 rounded-lg bg-green-50 text-green-700 px-4 py-3">
            {{ __('auth.verify.link_sent') }}
          </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="space-y-4">
          @csrf
          <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90" style="background-color: #2e3192;">
            {{ __('auth.verify.resend') }}
          </button>
        </form>

        <div class="mt-6 text-sm text-slate-500">
          <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-slate-600 hover:text-primary-blue">
            {{ __('auth.verify.logout') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </main>
</x-layout.auth>
