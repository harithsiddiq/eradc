<!-- Hero -->
@if ($category)
{{-- @dd($post, $category) --}}
<section id="home" class="relative overflow-hidden">
  <div class="absolute inset-0"
    style="z-index:0; pointer-events:none; background-image: linear-gradient(to bottom right, #2563eb 0%, #fdf6ec 40%, #ffffff 55%, #93c5fd 100%);">
  </div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-10 py-12 sm:py-16 lg:py-20 items-center">
      <div class="copy-section">
        <div
          class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-blue/10 text-primary-blue text-xs font-semibold mb-4">
          <span class="inline-block w-2 h-2 rounded-full bg-brand-600"></span>
          {{ $category->name }}
        </div>
        {!! str($category->description)->markdown()->sanitizeHtml() !!}
        <p class="mt-4 text-gray-600 text-base sm:text-lg max-w-2xl">
          {!! str($post?->content ?? '')->markdown()->sanitizeHtml() !!}
        </p>
        {{-- Debug: Locale={{ app()->getLocale() }} Session={{ session('locale') }} --}}
        @php($reservedKeys = ['hero_btn_primary', 'hero_btn_secondary', 'hero_btn_primary_url', 'hero_btn_secondary_url'])
        @php($heroMeta = $post?->meta ?? collect())
        @php($findMeta = fn($key) => $heroMeta->first(fn($m) => collect($m->getTranslations('meta_key'))->contains($key)))
        @php($btnPrimary = optional($findMeta('hero_btn_primary'))->getTranslation('meta_value', app()->getLocale()) ?: __('explore_courses'))
        @php($btnSecondary = optional($findMeta('hero_btn_secondary'))->getTranslation('meta_value', app()->getLocale()) ?: __('free_consultation'))
        @php($btnPrimaryUrl = optional($findMeta('hero_btn_primary_url'))->getTranslation('meta_value', app()->getLocale()) ?: '#courses')
        @php($btnSecondaryUrl = optional($findMeta('hero_btn_secondary_url'))->getTranslation('meta_value', app()->getLocale()) ?: '#contact')
        <div class="mt-6 flex flex-wrap items-center justify-center text-center gap-3 hero-actions"
          style="justify-content:center;">
          <a href="{{ $btnPrimaryUrl }}"
            class="inline-flex items-center px-5 py-3 rounded-xl bg-brand-600 text-white font-semibold shadow-glow hover:bg-brand-700">
            {{ $btnPrimary }}
            <i class="fi fi-rr-arrow-right ml-2 w-4 h-4"></i>
          </a>
          <a href="{{ $btnSecondaryUrl }}"
            class="inline-flex items-center px-5 py-3 rounded-xl border border-slate-300 text-gray-900 font-semibold hover:bg-slate-50">{{ $btnSecondary }}</a>
        </div>
        @php($heroMetrics = $heroMeta->filter(fn($m) => !collect($m->getTranslations('meta_key'))->intersect($reservedKeys)->count()))
        @if($heroMetrics->count())
          <div class="mt-8 flex items-center justify-center gap-6 text-sm text-gray-600 hero-metrics"
            style="justify-content:center;">
            @foreach($heroMetrics as $metric)
              <div class="flex items-center gap-2">
                <img src="./assets/up.svg" alt="up" width="25">
                {{ $metric->getTranslation('meta_value', app()->getLocale()) }}
                {!! nl2br(e($metric->getTranslation('meta_key', app()->getLocale()))) !!}
              </div>
            @endforeach
          </div>
        @endif
      </div>
      <div class="relative">
        <div class="relative mx-auto w-[320px] h-[320px] sm:w-[380px] sm:h-[380px]">
          <img src="{{ $post?->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : '' }}"
            alt="Hero" class="absolute inset-0 w-full h-full object-cover rounded-2xl">
        </div>
      </div>
    </div>
  </div>
</section>
@endif
