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
          <div class="swiper mySwiper h-[280px] w-full">
            <div class="swiper-wrapper">
              @foreach ($posts as $post)
                <div class="swiper-slide">
                  <div class="relative h-[280px] rounded-2xl overflow-hidden shadow-none">
                    <img
                      src="{{ $post->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : '' }}"
                      alt="{{ $post->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0"
                      style="background: linear-gradient(to bottom, rgba(46,49,146,0.70) 0%, rgba(46,49,146,0.50) 40%, rgba(0,0,0,0.00) 100%);">
                    </div>
                    <div class="absolute left-0 right-0 bottom-0 flex items-center justify-between gap-3 p-4">
                      <div class="flex flex-col">
                        <span style="color: #fff; font-size: 1rem; font-weight: 800; text-align: start; width: 50%">
                          {!! $post->title !!}
                        </span>
                      </div>
                      <a href="#" aria-label="{{ __('show_more') }}"
                        style="width: 44px; height: 44px; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.12); backdrop-filter: blur(2px); align-self: end;">
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