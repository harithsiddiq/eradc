<!-- Projects: مشاريعنا الهندسية ومواردنا العلمية -->
@if ($posts->count())
  <section id="projects" class="py-12 sm:py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-right max-w-6xl ml-auto">
        <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->name }}</h3>
        <p class="mt-2 text-gray-600 text-sm">
          {!! $category->description !!}
        </p>

        <div class="mt-8 flex flex-wrap justify-start gap-6">
          @foreach ($posts as $post)
            <article class="relative overflow-hidden shadow-sm"
              style="width: 280px; height: 280px; border-radius: 16px; background: #dbeafe;">
              <img
                src="{{ $post->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : '' }}"
                alt="{{ $post->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
              <div class="absolute inset-0"
                style="background: linear-gradient(to top, rgba(15,23,42,0.72) 0%, rgba(15,23,42,0.35) 45%, rgba(15,23,42,0.10) 100%);">
              </div>
              <div class="absolute left-0 right-0 bottom-0 p-5" style="padding-inline-end: 84px;">
                <h4 class="text-white text-2xl font-extrabold leading-tight text-right" style="max-width: 100%;">
                  {!! $post->title !!}
                </h4>
              </div>
              <a href="{{ route('posts.show', $post->slug) }}" aria-label="{{ __('show_more') }}"
                class="absolute inline-flex items-center justify-center"
                style="inset-inline-end: 20px; bottom: 20px; width: 44px; height: 44px; border-radius: 9999px; background: rgba(255, 255, 255, 0.2); border: 1px solid rgba(255, 255, 255, 0.2); backdrop-filter: blur(2px);">
                <i class="fi fi-rr-arrow-left" style="color:#fff; font-size:20px; margin-top:5px;"></i>
              </a>
            </article>
          @endforeach
        </div>
      </div>
    </div>
  </section>
@endif
