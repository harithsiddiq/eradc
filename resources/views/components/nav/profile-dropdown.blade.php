@props(['mobile' => false])

<style>
  .nav-dropdown-menu {
    top: 100%;
    margin-top: 0.5rem;
    width: 14rem;
    border: 1px solid rgba(0,0,0,0.05);
    @if(app()->isLocale('ar'))
      left: 0;
      right: auto;
    @else
      right: 0;
      left: auto;
    @endif
  }
  .nav-dropdown-menu .divide-item + .divide-item {
    border-top: 1px solid #f1f5f9;
  }
  .nav-profile-avatar {
    width: 2.25rem;
    height: 2.25rem;
  }
  .nav-profile-avatar-mobile {
    width: 2.5rem;
    height: 2.5rem;
  }
  .d-none {
    display: none !important;
  }
</style>

@if($mobile)
  <!-- Mobile version: Inline links -->
  <div class="px-4 py-3 border-t border-slate-100">
    <div class="flex items-center gap-3 mb-3">
      <div class="nav-profile-avatar-mobile rounded-full bg-primary-blue text-white flex items-center justify-center font-bold text-lg" style="background-color: #2e3192;">
        {{ Str::upper(substr(auth()->user()->name, 0, 1)) }}
      </div>
      <div>
        <div class="font-medium text-slate-900">{{ auth()->user()->name }}</div>
        <div class="text-xs text-slate-500">{{ auth()->user()->email }}</div>
      </div>
    </div>
    <div class="space-y-1">
      <a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-50 text-slate-700">
        {{ __('nav.profile') }}
      </a>
      <a href="#" class="block px-3 py-2 rounded-md hover:bg-slate-50 text-slate-700">
        {{ __('nav.my_courses') }}
      </a>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="w-full text-start px-3 py-2 rounded-md text-red-600 hover:bg-red-50">
          {{ __('nav.logout') }}
        </button>
      </form>
    </div>
  </div>
@else
  <!-- Desktop version: Dropdown -->
  <div id="nav-profile-dropdown-wrapper" class="relative hidden sm:inline-flex">
    <button onclick="document.getElementById('nav-profile-dropdown-menu').classList.toggle('d-none')" class="flex items-center gap-2 px-2 py-1 focus:outline-none hover:opacity-80 transition">
      <div class="nav-profile-avatar rounded-full bg-primary-blue text-white flex items-center justify-center font-semibold text-sm" style="background-color: #2e3192;">
        {{ Str::upper(substr(auth()->user()->name, 0, 1)) }}
      </div>
      <div class="hidden md:block text-sm font-medium text-slate-700">
        {{ auth()->user()->name }}
      </div>
      <svg class="w-4 h-4 text-slate-500 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
      </svg>
    </button>

    <div id="nav-profile-dropdown-menu" class="d-none absolute nav-dropdown-menu rounded-lg shadow-lg bg-white focus:outline-none z-50">
      
      <div class="px-4 py-3 divide-item">
        <p class="text-sm font-medium text-slate-900 truncate">{{ auth()->user()->name }}</p>
        <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
      </div>

      <div class="py-1 divide-item">
        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900">
          {{ __('nav.profile') }}
        </a>
        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900">
          {{ __('nav.my_courses') }}
        </a>
      </div>

      <div class="py-1 divide-item">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="w-full text-start px-4 py-2 text-sm text-red-600 hover:bg-red-50">
            {{ __('nav.logout') }}
          </button>
        </form>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var wrapper = document.getElementById('nav-profile-dropdown-wrapper');
      var menu = document.getElementById('nav-profile-dropdown-menu');
      if (wrapper && menu) {
        document.addEventListener('click', function(event) {
          if (!wrapper.contains(event.target)) {
            menu.classList.add('d-none');
          }
        });
      }
    });
  </script>
@endif
