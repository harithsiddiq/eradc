@props(['mobile' => false])

@guest
  @if($mobile)
    <a href="{{ route('login') }}" class="login-btn" aria-label="Login">{{ __('navbar.login') }}</a>
  @else
    <a href="{{ route('login') }}"
      class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">{{ __('navbar.login') }}</a>
    <a href="{{ route('register') }}"
      class="hidden sm:inline-flex items-center px-3 py-2 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90">{{ __('navbar.register') }}</a>
  @endif
@else
  <x-nav.profile-dropdown :mobile="$mobile" />
@endguest
