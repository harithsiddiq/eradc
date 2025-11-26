<x-layout.app>
<main class="py-8 sm:py-12">
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-right">
      <h1 class="text-2xl sm:text-3xl font-extrabold text-primary-blue">{{ __('posts.index.title') }}</h1>
      <p class="mt-2 text-gray-600 text-sm">{{ __('posts.index.subtitle') }}</p>
    </div>

    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"  style="min-height: 40vh">
      @forelse($posts as $p)
        @php($title = $p->getTranslation('title', app()->getLocale(), false) ?? $p->title)
        @php($excerpt = $p->getTranslation('excerpt', app()->getLocale(), false))
        @php($content = $p->getTranslation('content', app()->getLocale(), false))
        @php($text = is_string($excerpt) ? $excerpt : (is_string($content) ? $content : ''))
        @php($text = strip_tags($text))
        @php($minutes = max(1, (int) ceil(str_word_count($text) / 200)))
        <a href="{{ route('posts.show', $p->slug) }}" class="group rounded-2xl border border-slate-200 bg-white p-0 shadow-card hover:shadow-glow transition overflow-hidden">
          <img src="{{ $p->featured_image_path ? Storage::disk('public')->url($p->featured_image_path) : asset('assets/courses.jpg') }}" alt="Featured" class="w-full h-[180px] object-cover" />
          <div class="p-5">
            <h3 class="font-semibold text-lg text-slate-900 group-hover:text-primary-blue transition-colors">{{ $title }}</h3>
            <p class="mt-2 text-sm text-gray-700">{{ str($text)->limit(150) }}</p>
            <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-user"></i> {{ $p->author->name ?? 'Admin' }}</span>
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-calendar"></i> {{ optional($p->published_at ?? $p->created_at)->format(app()->getLocale()==='ar' ? 'd M Y' : 'M d, Y') }}</span>
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-time-add"></i> {{ $minutes }} {{ app()->getLocale()==='ar' ? 'دقيقة' : 'min' }}</span>
            </div>
          </div>
        </a>
      @empty
        <div class="col-span-full text-center text-slate-500">{{ app()->getLocale()==='ar' ? 'لا توجد مقالات' : 'No articles found' }}</div>
      @endforelse
    </div>

    <div class=" flex items-center justify-between mt-4">
      <a href="{{ route('home') }}#home" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-300 hover:bg-slate-50 text-sm">
        <i class="fi fi-rr-arrow-right"></i>
        <span>{{ app()->getLocale()==='ar' ? 'العودة للرئيسية' : 'Back to Home' }}</span>
      </a>
      <div class="flex items-center gap-2 text-sm">
        {{ $posts->onEachSide(1)->links() }}
      </div>
    </div>
  </section>
</main>
</x-layout.app>
