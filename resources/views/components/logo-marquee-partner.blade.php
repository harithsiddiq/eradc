<!-- Logo Marquee: شريكنا الاستراتيجي -->
@if ($category && $posts)
  <section id="logo-marquee" class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="text-center">
        <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
      </div>
      <div class="flex flex-wrap items-center justify-center gap-6">
        @forelse ($posts as $singlePost)
          <div class="flex items-center justify-center w-40 h-20">
            <img
              src="{{ $singlePost->featured_image_path ? Storage::disk('public')->url($singlePost->featured_image_path) : '' }}"
              alt="{{ $singlePost->title }}" class="max-h-full max-w-full object-contain" />
          </div>
        @empty
          <div class="flex items-center justify-center w-40 h-20">
            <img src="{{ asset('assets/logo.svg') }}" alt="logo1" class="max-h-full max-w-full object-contain" />
          </div>
        @endforelse
      </div>
    </div>
  </section>
@endif