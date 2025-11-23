<!-- Infra Projects: ابرز مشاريعنا للبنية التحتية -->
@if ($posts->count() > 0)
<section id="infra-projects" class="py-12 sm:py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-right max-w-6xl ml-auto">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">
        {{ $category->name }}
      </h3>
      <div class="logo-grid">
        @forelse ($posts as $singlePost)
            <div class="logo-item"><img src="{{ $singlePost->featured_image_path ? Storage::disk('public')->url($singlePost->featured_image_path) : '' }}" alt="{{ $singlePost->title }}"></div>
        @empty
            <div class="logo-item"><img src="{{ asset('assets/logo.svg') }}" alt="شريك 1"></div>
        @endforelse
      </div>
    </div>
  </div>
</section>
@endif
