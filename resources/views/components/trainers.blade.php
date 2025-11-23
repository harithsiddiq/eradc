@if ($category && $posts)
<section id="trainer" class="py-12 sm:py-16 text-white" style="background-color: #2e3192;">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white mb-6">{{ $category->name }}</h3>
    </div>
    <div class="py-16 grid sm:grid-cols-2 gap-8">
    @foreach ($posts as $singlePost)
      <div class="flex flex-col items-center p-8 gap-4 text-slate-500 bg-white rounded-2xl">
        <img class="rounded-full" src="{{ $singlePost->featured_image_path ? Storage::disk('public')->url($singlePost->featured_image_path) : '' }}" alt="trainer1" style="width: 170px; height: 170px; object-fit: cover;"/>
        <span class="text-xl font-bold text-primary-blue">{{ $singlePost->title }}</span>
        <span class="text-md text-center text-gray-400 line-clamp-3">
            {!! str($singlePost->content)->markdown()->sanitizeHtml() !!}
        </span>
        <div class="mt-2"></div>
        <div class="mt-2 w-50">
          <div class="flex justify-between gap-8 text-primary-blue">
            <div class="text-center">
              <span class="block text-blue-600 bold">{{ optional($singlePost->meta->firstWhere('meta_key', 'expert'))->getTranslation('meta_value', app()->getLocale()) }}</span>
              <span>سنوات الخبرة</span>
            </div>
            <div class="text-center">
              <span class="block text-blue-600 font-bold">{{ optional($singlePost->meta->firstWhere('meta_key', 'projects'))->getTranslation('meta_value', app()->getLocale()) }}</span>
              <span>المشاريع المكتملة</span>
            </div>
            <div class="text-center">
              <span class="block text-blue-600 font-bold">{{ optional($singlePost->meta->firstWhere('meta_key', 'trainers'))->getTranslation('meta_value', app()->getLocale()) }}</span>
              <span>متدرب</span>
            </div>
          </div>
        </div>
        <div class="mt-4 justify-center flex">
          <span class="inline-flex items-center justify-center gap-3 text-blue-600 font-semibold">
            <span>عرض المزيد</span>
            <a href="#" aria-label="عرض المزيد" style="width: 44px; height: 44px; border-radius: 9999px; border: 1px solid #2e3192; display: inline-flex; align-items: center; justify-content: center; backdrop-filter: blur(2px); align-self: end;">
              <i class="fi fi-rr-arrow-left" style="color:#2e3192; font-size:20px; margin-top:5px;"></i>
            </a>
          </span>
        </div>
      </div>
    @endforeach

    </div>
  </div>
</section>
@endif
