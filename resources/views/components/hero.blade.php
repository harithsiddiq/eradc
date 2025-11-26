<!-- Hero -->
@if ($post && $category)
{{-- @dd($post, $category) --}}
<section id="home" class="relative overflow-hidden">
  <div class="absolute inset-0" style="z-index:0; pointer-events:none; background-image: linear-gradient(to bottom right, #2563eb 0%, #fdf6ec 40%, #ffffff 55%, #93c5fd 100%);"></div>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid lg:grid-cols-2 gap-10 py-12 sm:py-16 lg:py-20 items-center">
      <div class="copy-section">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary-blue/10 text-primary-blue text-xs font-semibold mb-4">
          <span class="inline-block w-2 h-2 rounded-full bg-brand-600"></span>
          {{ $category->name }}
        </div>
          {!! str($category->description)->markdown()->sanitizeHtml() !!}
        <p class="mt-4 text-gray-600 text-base sm:text-lg max-w-2xl">
          {!! str($post->content)->markdown()->sanitizeHtml() !!}
        </p>
        <div class="mt-6 flex flex-wrap items-center justify-center text-center bg-red-700 gap-3 hero-actions" style="justify-content:center;">
          <a href="#courses" class="inline-flex items-center px-5 py-3 rounded-xl bg-brand-600 text-white font-semibold shadow-glow hover:bg-brand-700">
            استكشف الدورات
            <i class="fi fi-rr-arrow-right ml-2 w-4 h-4"></i>
          </a>
          <a href="#contact" class="inline-flex items-center px-5 py-3 rounded-xl border border-slate-300 text-gray-900 font-semibold hover:bg-slate-50">احصل على استشارة مجانية</a>
        </div>
        <div class="mt-8 flex items-center justify-center gap-6 text-sm text-gray-600 hero-metrics" style="justify-content:center;">
          <div class="flex items-center gap-2"><img src="./assets/up.svg" alt="up" width="25">{{ optional($post->meta->firstWhere('meta_key', 'مشروع محلي معتمد'))->getTranslation('meta_value', app()->getLocale()) }} مشروع <br /> محلي معتمد</div>
          <div class="flex items-center gap-2"><img src="./assets/up.svg" alt="up" width="25">{{ optional($post->meta->firstWhere('meta_key', ' نسبة فرص المتدربين'))->getTranslation('meta_value', app()->getLocale()) }} نسبة <br /> فرص المتدربين</div>
        </div>
      </div>
      <div class="relative">
        <div class="relative mx-auto w-[320px] h-[320px] sm:w-[380px] sm:h-[120px]">
          <img src="{{ $post->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : '' }}" alt="Hero" class="absolute inset-0 w-full h-full object-cover rounded-2xl">
        </div>
      </div>
    </div>
  </div>
</section>
@endif
