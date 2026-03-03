<!-- Infra Projects: ابرز مشاريعنا للبنية التحتية -->
@if ($posts->count() > 0)
  <section id="infra-projects" class="py-12 sm:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-right max-w-6xl ml-auto">
               <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">
          {{ $category->name }}
        </h3>
        <div class="mt-8">
          <div class="swiper infraSwiper" style="width: 100%;">
            <div class="swiper-wrapper">
              @forelse ($posts as $singlePost)
                <div class="swiper-slide" style="height: 280px;">
                  <article class="relative overflow-hidden shadow-sm"
                    style="width: 100%; height: 280px; border-radius: 16px; background: #dbeafe;">
                    <img
                      src="{{ $singlePost->featured_image_path ? Storage::disk('public')->url($singlePost->featured_image_path) : '' }}"
                      alt="{{ $singlePost->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                  </article>
                </div>
              @empty
                <div class="swiper-slide" style="height: 280px;">
                  <article class="relative overflow-hidden shadow-sm"
                    style="width: 100%; height: 280px; border-radius: 16px; background: #dbeafe;">
                    <img src="{{ asset('assets/logo.svg') }}" alt="{{ __('partner') }} 1"
                      class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                  </article>
                </div>
              @endforelse
            </div>
            <div class="swiper-pagination infra-swiper-pagination"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endif
