<!-- Logo Marquee: المعايير القياسية -->
@if ($category->posts->count())
<section id="logo-marquee" class="py-16 bg-slate-50">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="text-center">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
    </div>
    <div class="logo-swiper swiper">
      <div class="swiper-wrapper flex justify-center">
        @php($firstPost = $posts->first())
        @foreach (($firstPost?->additional_images ?? []) as $img)
          @php($media = is_numeric($img) ? \App\Models\Media::find($img) : null)
          @php($src = $media?->file_path ?? $img)
          @php($url = $src ? Storage::disk('public')->url($src) : '')
          <div class="swiper-slide"><img src="{{ $url }}" alt="{{ $media?->getTranslation('alt_text', app()->getLocale()) }}" /></div>
        @endforeach
      </div>
    </div>
  </div>
</section>
@endif
