<!-- Logo Marquee: المعايير القياسية -->
@if ($category->posts->count())
<section id="logo-marquee" class="py-16 bg-slate-50">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="text-center">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
    </div>
    <div class="logo-swiper swiper" style="width: 100%;">
      <div class="swiper-wrapper">
        @php($firstPost = $posts->first())
        @forelse (($firstPost?->additional_images ?? []) as $img)
          @php($media = is_numeric($img) ? \App\Models\Media::find($img) : null)
          @php($src = $media?->file_path ?? $img)
          @php($url = $src ? Storage::disk('public')->url($src) : '')
          <div class="swiper-slide" style="height: 280px;">
            <article class="relative overflow-hidden shadow-sm"
              style="width: 100%; height: 280px; border-radius: 16px; background: #dbeafe;">
              <img src="{{ $url }}" alt="{{ $media?->getTranslation('alt_text', app()->getLocale()) }}"
                class="absolute inset-0 h-full w-full object-contain" style="object-fit: contain !important" loading="lazy" />
            </article>
          </div>
        @empty
          <div class="swiper-slide" style="height: 280px;">
            <article class="relative overflow-hidden shadow-sm"
              style="width: 100%; height: 280px; border-radius: 16px; background: #dbeafe;">
              <img src="{{ asset('assets/logo.svg') }}" alt="{{ __('partner') }}"
                class="absolute inset-0 h-full w-full object-cover" loading="lazy" />
            </article>
          </div>
        @endforelse
      </div>
      <div class="swiper-pagination standards-swiper-pagination"></div>
    </div>
  </div>
</section>
@endif
