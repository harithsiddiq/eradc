<!-- Logo Marquee: شريكنا الاستراتيجي -->
@if ($category && $posts)
<section id="logo-marquee" class="py-16 bg-slate-50">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="text-center">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
    </div>
    <div class="flex justify-center">
      @forelse ($posts as $singlePost)
        <div><img src="{{ $singlePost->featured_image_path ? Storage::disk('public')->url($singlePost->featured_image_path) : '' }}" alt="{{ $singlePost->title }}"></div>
      @empty
        <div><img src="{{ asset('assets/logo.svg') }}" alt="logo1" width="200" /></div>
      @endforelse
    </div>
  </div>
</section>
@endif
