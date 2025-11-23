<x-layout.auth>
    <main class="py-10 flex-1" style="display:flex; align-items:center; justify-content:center;">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-10 items-center">

        <div class="lg:col-span-1">
          <div class="rounded-2xl p-6 ">
            <h2 class="text-xl font-bold">{{ __('auth.register.title') }}</h2>
            <form class="mt-4 space-y-4" action="{{ route('register') }}" method="POST">
              @csrf
              <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('auth.register.name') }}</label>
                <input name="name" value="{{ old('name') }}" style="border: 1px solid #2e3192;" type="text" placeholder="Jane Doe" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-primary-blue focus:border-primary-blue" required />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('auth.register.email') }}</label>
                <input name="email" value="{{ old('email') }}" style="border: 1px solid #2e3192;" type="email" placeholder="you@example.com" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-primary-blue focus:border-primary-blue" required />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('auth.register.password') }}</label>
                <input name="password" style="border: 1px solid #2e3192;" type="password" placeholder="••••••••" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-primary-blue focus:border-primary-blue" required />
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700">{{ __('auth.register.password_confirm') }}</label>
                <input name="password_confirmation" style="border: 1px solid #2e3192;" type="password" placeholder="••••••••" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2 focus:ring-primary-blue focus:border-primary-blue" required />
              </div>
            <!-- Level selection toggle -->
              <div class="level-toggle mb-2 flex justify-center" >
                <label class="option"><input type="radio" name="level" value="fresh" checked> حديث التخرج</label>
                <label class="option"><input type="radio" name="level" value="mid"> متوسط الخبرة</label>
                <label class="option"><input type="radio" name="level" value="consultant"> استشاري</label>
              </div>
              <!-- Agreement and submit on the same line -->
              <div class="flex items-center justify-between gap-3">
                <label class="inline-flex items-center gap-2 text-sm"><input type="checkbox" class="rounded border-slate-300" required> {{ __('auth.register.agree_prefix') }} <a href="#" class="text-cyan-accent">{{ __('auth.register.terms') }}</a></label>
                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90"  style="background-color: #2e3192;">{{ __('auth.register.submit') }}</button>
              </div>
              @if ($errors->any())
                <div class="mt-2 text-sm text-red-600">{{ $errors->first() }}</div>
              @endif
            </form>
          </div>
        </div>

        <div class="lg:block">
          <img src="{{ asset('assets/logo.svg') }}" alt="EradcHub" width="300" class="block mx-auto lg:mx-0">
          <div class="flex gap-4 mt-2 items-center">
              <h1 class="text-xl font-extrabold w-50 text-secondary-blue flex-1" style="width: 300px;">
                {{ __('auth.register.hero_line1') }}
                {{ __('auth.register.hero_line2') }}
              </h1>
            <!-- Vertical divider on desktop -->
            <div class="hidden md:block w-px bg-slate-200 self-stretch"></div>
            <div class="flex-1">
              <span>{{ __('auth.register.have_account_title') }}</span>
              <br />
              <a href="{{ route('login') }}" class="bg-primary-blue text-white px-4 py-2 mt-2 block text-center rounded-lg" style="background-color: #2e3192;">{{ __('auth.register.sign_in') }} <i class="fi fi-rr-arrow-right"></i></a>
            </div>
          </div>
          <!-- Horizontal divider on mobile -->
          <hr class="md:hidden border-slate-200 my-4" />
        </div>


      </div>
    </main>
</x-layout.auth>
