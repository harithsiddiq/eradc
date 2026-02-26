
<x-layout.app>

  <style>
    .prose ol {
      list-style-type: decimal !important;
      padding-inline-start: 1.5em !important;
    }

    .prose ul {
      list-style-type: disc !important;
      padding-inline-start: 1.5em !important;
    }

    .prose li {
      margin-block: 0.25em;
    }

    ul {
      margin: unset !important;
    }
  </style>
  <main class="bg-white text-neutral-dark antialiased overflow-x-hidden">
    <!-- Hero -->
    <section class="py-10 bg-gradient-to-b from-primary-blue/5 to-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-8 items-start">
          <div class="lg:col-span-2">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $post->title }}</h1>
            <p class="mt-3 text-gray-600">{!!  $post->excerpt !!}</p>
            @if(!empty($post->featured_image_path))
            @php($featuredImagePath = ltrim($post->featured_image_path, '/'))
            @php($featuredImagePath = str_starts_with($featuredImagePath, 'storage/') ? substr($featuredImagePath, 8) : $featuredImagePath)
            <img src="{{ asset('storage/' . $featuredImagePath) }}" alt="{{ $post->title }}"
              class="mt-6 w-full h-auto max-h-96 object-cover rounded-2xl" loading="lazy" />
            @endif
            <div class="mt-4 flex items-center gap-4 hidden text-sm text-gray-600">
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-check"></i>35 Contact Hours</span>
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-book-alt"></i>Exam Prep Kit</span>
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-calendar"></i>Weekend Batches</span>
            </div>
          </div>
          <aside class="rounded-2xl bg-white p-6 border border-slate-200 shadow">
            @php($parentCategory = optional($post->category)->parent)
            @php($parentPost = $parentCategory ? $parentCategory->posts()->first() : null)
            @php($resolveMeta = function (string $key, $default = null) use ($post, $parentPost) {
              $meta = $parentPost?->meta->firstWhere('meta_key', $key) ?: $post->meta->firstWhere('meta_key', $key);
              if (!$meta) {
                return $default;
              }

              return $meta->getTranslation('meta_value', app()->getLocale(), false) ?? $meta->meta_value ?? $default;
            })
            @php($priceMeta = $parentPost?->meta->firstWhere('meta_key', 'price') ?: $post->meta->firstWhere('meta_key', 'price'))
            @php($price = $priceMeta ? $priceMeta->meta_value : null)
            @php($priceNote = $resolveMeta('price_note', __('courses.price_note')))
            @php($ctaLabel = $resolveMeta('enroll_button_text', __('courses.enroll_now')))
            @php($ctaUrl = $resolveMeta('enroll_button_url', url('index.html#contact')))
            @php($guidanceText = $resolveMeta('guidance_text', __('courses.need_guidance')))
            @php($guidanceLinkText = $resolveMeta('guidance_link_text', __('courses.talk_to_advisor')))
            @php($guidanceLinkUrl = $resolveMeta('guidance_link_url', '#'))
            <div class="text-2xl font-extrabold">{{ $price ?? '$399' }}</div>
            <p class="text-xs text-gray-500">{{ $priceNote }}</p>
            <a href="{{ $ctaUrl }}"
              class="mt-3 inline-flex items-center justify-center w-full px-4 py-2 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90">{{ $ctaLabel }}</a>
            <p class="mt-3 text-xs text-gray-500">{{ $guidanceText }} <a class="text-cyan-accent"
                href="{{ $guidanceLinkUrl }}">{{ $guidanceLinkText }}</a>.</p>
          </aside>
        </div>
      </div>
    </section>

    <section class=" h-1/2">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <section id="levels" class="py-12 sm:py-16 bg-slate-0" lang="ar" dir="rtl">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-right max-w-6xl ml-auto">
              <div class="prose prose-slate max-w-none">
                {!! str($post->getTranslation('content', app()->getLocale(), false) ?? $post->content)->sanitizeHtml() !!}
              </div>
            </div>
          </div>
        </section>
      </div>
    </section>

    @php($additionalImageIds = is_array($post->additional_images ?? null) ? $post->additional_images : [])
    @php($additionalMedia = count($additionalImageIds) ? \App\Models\Media::whereIn('id', $additionalImageIds)->get() : collect())
    @if($additionalMedia->count())
    <section class="py-10 h-1/2">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <section class="py-4 sm:py-4 bg-slate-0" lang="ar" dir="rtl">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
              @foreach($additionalMedia as $media)
              @php($galleryPath = ltrim($media->file_path, '/'))
              @php($galleryPath = str_starts_with($galleryPath, 'storage/') ? substr($galleryPath, 8) : $galleryPath)
              <a href="{{ asset('storage/' . $galleryPath) }}"
                class="block overflow-hidden rounded-2xl border border-slate-200 bg-white shadow">
                <img src="{{ asset('storage/' . $galleryPath) }}"
                  alt="{{ $media->getTranslation('alt_text', app()->getLocale(), false) ?? $post->title }}"
                  class="w-full h-56 object-cover" loading="lazy" />
              </a>
              @endforeach
            </div>
          </div>
        </section>
      </div>
    </section>
    @endif

    <section class="py-10 h-1/2">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <section id="levels" class="py-4 sm:py-4 bg-slate-0" lang="ar" dir="rtl">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(isset($category) && $category && $category->children->count())
            @foreach($category->children as $child)
            @php($layout = $child->layout_style)
            @php($view = $layout ? ('components.' . $layout) : null)
            @if($view && \Illuminate\Support\Facades\View::exists($view))
              @include($view, ['category' => $child, 'posts' => $child->posts])
            @endif
            @endforeach
            @endif
          </div>
        </section>
      </div>
    </section>


    <section id="projects" class="py-12 sm:py-16 hidden text-center">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-right max-w-6xl ml-auto">
          <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">تقيمات المشتركين </h3>
          <div class="services-accordion mt-8 space-y-3">
            <div class="swiper mySwiper" style="height: 280px; width: 100% !important">
              <div class="swiper-wrapper">
                <div class="swiper-slide">
                  <div class="relative flex"
                    style="height: 280px; border-radius: 16px; overflow: hidden; box-shadow: none;">
                    <img src="{{ asset('assets/courses.jpg') }}" alt="شهادة متدرب"
                      style="width: 30%; height: 100%; object-fit: cover; display: block;">
                    <div
                      style="width: 70%; background-color: #2e3192; color: #ffffff; display: flex; flex-direction: column; justify-content: center; gap: 12px; padding: 20px;">
                      <div style="text-align: center;">
                        <h4 style="font-size: 20px; font-weight: 800;">م. راكان الشهري</h4>
                        <div
                          style="width: 80px; height: 2px; background-color: rgba(255,255,255,0.7); margin: 6px auto 0;">
                        </div>
                      </div>
                      <p style="font-size: 14px; line-height: 1.6; text-align: right;">
                        “ دورات متخصصة في الهندسة الكهربائية تغطي أحدث التقنيات والمعايير الدولية في مجال مراكز البيانات
                        والأنظمة الكهربائية من التيار العالي والمنخفض ”
                      </p>
                      <div style="display:flex; align-items:center; gap:8px; justify-content: center;">
                        <span>ينصح به</span>
                        <i class="fi fi-rr-star" style="color:#f59e0b;"></i>
                        <i class="fi fi-rr-star" style="color:#f59e0b;"></i>
                        <i class="fi fi-rr-star" style="color:#f59e0b;"></i>
                        <i class="fi fi-rr-star" style="color:#f59e0b;"></i>
                        <i class="fi fi-rr-star" style="color:#f59e0b;"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="swiper-pagination"></div>
              </div>
            </div>
          </div>
        </div>
    </section>

  </main>
</x-layout.app>