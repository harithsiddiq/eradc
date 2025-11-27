<!-- Projects: مشاريعنا الهندسية ومواردنا العلمية -->
@if ($posts->count())
<section id="projects" class="py-12 sm:py-16 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-right max-w-6xl ml-auto">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
      <p class="mt-2 text-gray-600 text-sm">
        {!! $category->description !!}
      </p>

      <div class="services-accordion mt-8 space-y-3">
        <div class="swiper mySwiper" style="height: 280px; width: 100% !important">
          <div class="swiper-wrapper">
            @foreach ($posts as $post)
            <div class="swiper-slide">
              <div class="relative" style="height: 280px; border-radius: 16px; overflow: hidden; box-shadow: none;">
                <img src="{{ $post->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : '' }}" alt="{{ $post->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                <div style="position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(46,49,146,0.70) 0%, rgba(46,49,146,0.50) 40%, rgba(0,0,0,0.00) 100%);"></div>
                <div style="position: absolute; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px;">
                  <div class="flex flex-col">
                    <span style="color: #fff; font-size: 1rem; font-weight: 800; text-align: start; width: 50%">
                        {!! $post->title !!}
                    </span>
                  </div>
                  <a href="{{ route('post.show', $post->slug) }}" aria-label="عرض المزيد" style="width: 44px; height: 44px; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.12); backdrop-filter: blur(2px); align-self: end;">
                    <i class="fi fi-rr-arrow-left" style="color:#fff; font-size:20px; margin-top:5px;"></i>
                  </a>
                </div>
              </div>
            </div>
            @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </div>
  </div>
</section>
@endif
