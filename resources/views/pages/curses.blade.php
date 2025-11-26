<x-layout.app>

    <main class="bg-white text-neutral-dark antialiased overflow-x-hidden">
    <!-- Hero -->
    <section class="py-10 bg-gradient-to-b from-primary-blue/5 to-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-8 items-start">
          <div class="lg:col-span-2">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight">{{ $post->title }}</h1>
            <p class="mt-3 text-gray-600">{!!  $post->excerpt !!}</p>
            <div class="mt-4 flex items-center gap-4 text-sm text-gray-600">
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-check"></i>35 Contact Hours</span>
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-book-alt"></i>Exam Prep Kit</span>
              <span class="inline-flex items-center gap-2"><i class="fi fi-rr-calendar"></i>Weekend Batches</span>
            </div>
          </div>
          <aside class="rounded-2xl bg-white p-6 border border-slate-200 shadow">
            @php($priceMeta = $post->meta->firstWhere('meta_key', 'price'))
            @php($price = $priceMeta ? $priceMeta->getTranslation('meta_value', app()->getLocale(), false) : null)
            <div class="text-2xl font-extrabold">{{ $price ?? '$399' }}</div>
            <p class="text-xs text-gray-500">{{ __('courses.price_note') }}</p>
            <a href="{{ url('index.html#contact') }}" class="mt-3 inline-flex items-center justify-center w-full px-4 py-2 rounded-lg bg-primary-blue text-white font-semibold hover:opacity-90">{{ __('courses.enroll_now') }}</a>
            <p class="mt-3 text-xs text-gray-500">{{ __('courses.need_guidance') }} <a class="text-cyan-accent" href="#">{{ __('courses.talk_to_advisor') }}</a>.</p>
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
                    {!! str($post->getTranslation('content', app()->getLocale(), false) ?? $post->content)->markdown()->sanitizeHtml() !!}
                  </div>
                </div>
            </div>
        </section>
      </div>
    </section>

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
                       <div class="relative flex" style="height: 280px; border-radius: 16px; overflow: hidden; box-shadow: none;">
                         <img src="{{ asset('assets/courses.jpg') }}" alt="شهادة متدرب" style="width: 30%; height: 100%; object-fit: cover; display: block;">
                        <div style="width: 70%; background-color: #2e3192; color: #ffffff; display: flex; flex-direction: column; justify-content: center; gap: 12px; padding: 20px;">
                           <div style="text-align: center;">
                              <h4 style="font-size: 20px; font-weight: 800;">م. راكان الشهري</h4>
                              <div style="width: 80px; height: 2px; background-color: rgba(255,255,255,0.7); margin: 6px auto 0;"></div>
                           </div>
                           <p style="font-size: 14px; line-height: 1.6; text-align: right;">
                             “ دورات متخصصة في الهندسة الكهربائية تغطي أحدث التقنيات والمعايير الدولية في مجال مراكز البيانات والأنظمة الكهربائية من التيار العالي والمنخفض ”
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
