<!-- Logo Marquee: شريكنا الاستراتيجي -->
@if ($category && $posts)
  <section id="logo-marquee" class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="text-center">
        <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
      </div>
      <div class="flex flex-wrap items-center justify-center gap-6">
        @forelse ($posts as $singlePost)
          <div class="flex items-center justify-center" style="width: 200px; height: 100px;">
            <img
              src="{{ $singlePost->featured_image_path ? Storage::disk('public')->url($singlePost->featured_image_path) : '' }}"
              alt="{{ $singlePost->title }}" class="object-contain" style="width: 200px; height: auto; transform: scale(1.3); transform-origin: center;" />
          </div>
        @empty
          <div class="flex items-center justify-center" style="width: 200px; height: 100px;">
            <img src="{{ asset('assets/logo.svg') }}" alt="logo1" class="object-contain" style="width: 200px; height: auto; transform: scale(1.3); transform-origin: center;" />
          </div>
        @endforelse
      </div>
    </div>
  </section>
@endif
