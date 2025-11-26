<!-- Services -->
@if ($posts->count())
{{-- @dd($posts) --}}
<section id="services" class="py-12 sm:py-16 bg-slate-0">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-right max-w-6xl ml-auto">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
      <p class="mt-2 text-gray-600 text-sm">
        {!! str($category->description)->markdown()->sanitizeHtml() !!}
      </p>
      <div class="services-accordion mt-8 space-y-3">
        @forelse($posts as $singlePost)
        <details>
          <summary>
            <div class="flex items-center gap-3 justify-end">
              <span class="title">{{ $singlePost->title }}</span>
            </div>
            <i class="fi fi-rr-plus chevron-plus w-5 h-5" style="color:#fff;"></i>
            <i class="fi fi-rr-minus chevron-minus w-5 h-5" style="color:#fff;"></i>
          </summary>
          <div class="content">
            <ul class="mt-3 space-y-2">
                {!! str($singlePost->excerpt)->markdown()->sanitizeHtml() !!}
            </ul>
            <a href="{{ route('posts.show', $singlePost->slug) }}" class="action-btn"><span>تعرف أكثر</span><i class="fi fi-rr-arrow-left" style="margin-inline-start: 0.5rem; font-size: 16px; vertical-align: middle;"></i></a>
          </div>
        </details>
        @empty
            nothing
        @endforelse
      </div>
    </div>
  </div>
</section>
@endif
