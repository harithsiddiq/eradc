<x-layout.auth>
    <main class="py-10 flex-1" style="display:flex; align-items:center; justify-content:center;">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">
        <div class="lg:block">
          <img src="{{ asset('assets/logo.svg') }}" alt="EradcHub" width="300" class="block mx-auto lg:mx-0">
          <div class="flex gap-4 mt-2 items-center">
              <h1 class="text-xl font-extrabold w-50 text-secondary-blue flex-1" style="width: 300px;">
                {{ __('auth.login.hero_line1') }}
                {{ __('auth.login.hero_line2') }}
              </h1>
            <!-- Vertical divider on desktop -->
            <div class="hidden md:block w-px bg-slate-200 self-stretch"></div>
            <div class="flex-1">
              <span>{{ __('auth.login.no_account_title') }}</span>
              <br />
              <a href="{{ route('register') }}" class="bg-primary-blue text-white px-4 py-2 mt-2 block text-center rounded-lg" style="background-color: #2e3192;">{{ __('auth.login.create_account') }} <i class="fi fi-rr-arrow-right"></i></a>
            </div>
          </div>
          <!-- Horizontal divider on mobile -->
          <hr class="md:hidden border-slate-200 my-4" />
        </div>

        <div class="lg:col-span-1">
          <div class="rounded-2xl p-6 ">
            <h2 class="text-xl font-bold">{{ __('auth.login.title') }}</h2>
            <form class="mt-4 space-y-4" action="{{ route('login') }}" method="POST">
              @csrf
              <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('auth.login.email') }}</label>
                <input name="email" value="{{ old('email') }}" style="border: 1px solid #2e3192;" type="email" placeholder="you@example.com" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-primary-blue focus:border-primary-blue" required />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('auth.login.password') }}</label>
                <input name="password" style="border: 1px solid #2e3192;" type="password" placeholder="••••••••" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-primary-blue focus:border-primary-blue" required />
              </div>
              <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm"><input name="remember" type="checkbox" class="rounded border-slate-300"> {{ __('auth.login.remember') }}</label>
                <a href="#" class="text-sm text-cyan-accent">{{ __('auth.login.forgot') }}</a>
              </div>
              <div class="flex items-center justify-end">
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90" style="background-color: #2e3192;">{{ __('auth.login.submit') }}</button>
              </div>
              @if ($errors->any())
                <div class="mt-2 text-sm text-red-600">{{ $errors->first() }}</div>
              @endif
            </form>
          </div>
        </div>
      </div>
    </main>
</x-layout.auth>
