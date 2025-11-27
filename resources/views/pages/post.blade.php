<x-layout.app>
   <main class="py-8 sm:py-12">
      <!-- Breadcrumb -->
      <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-sm text-slate-600 text-right">
        <ol class="flex items-center gap-2 flex-wrap">
          <li><a href="{{ url('/') }}" class="text-cyan-accent hover:underline">Home</a></li>
          <li class="text-slate-400">/</li>
          <li><a href="{{ route('posts.index') }}" class="hover:underline">Blog</a></li>
          <li class="text-slate-400">/</li>
          <li class="text-slate-700">{{ $post->title }}</li>
        </ol>
      </nav>

      <!-- Header banner with gradient and centered title -->
      <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-3">
        <div class="relative h-[260px] rounded-2xl overflow-hidden" style="background-image: linear-gradient(to bottom right, #2563eb 0%, #fdf6ec 40%, #ffffff 55%, #93c5fd 100%);">
          <div class="absolute inset-0 flex items-center justify-center text-center px-4">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">How to Choose the Right Project Management Certification</h1>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm" lang="ar">
          <div class="flex items-center gap-4 text-slate-600">
            <span class="inline-flex items-center gap-2"><i class="fi fi-rr-user"></i> {{ app()->getLocale()==='ar' ? 'بواسطة' : 'By' }} {{ $post->author->name ?? 'Admin' }}</span>
            @php($dt = $post->published_at ?? $post->created_at)
            <span class="inline-flex items-center gap-2"><i class="fi fi-rr-calendar"></i> {{ optional($dt)->format(app()->getLocale()==='ar' ? 'd M Y' : 'M d, Y') }}</span>
            @php($contentText = is_array($post->content) ? implode(' ', array_values($post->content)) : strip_tags($post->content ?? ''))
            @php($minutes = max(1, (int) ceil(str_word_count($contentText) / 200)))
            <span class="inline-flex items-center gap-2"><i class="fi fi-rr-time-add"></i> {{ $minutes }} {{ app()->getLocale()==='ar' ? 'دقيقة قراءة' : 'min read' }}</span>
          </div>
          <div class="flex items-center gap-2">
            @php($footer = app(\App\Settings\FooterSettings::class))
            @if(is_array($footer->social_links) && count($footer->social_links))
              @foreach($footer->social_links as $link)
                @php($platform = $link['platform'] ?? '')
                <a href="{{ $link['url'] ?? '#' }}" target="_blank" rel="noopener" class="inline-flex items-center justify-center w-9 h-9 rounded-full border-2" style="border-color:#1e3a8a;">
                  <i class="{{ $platform === 'twitter' ? 'fi fi-brands-twitter-alt' : ($platform === 'linkedin' ? 'fi fi-brands-linkedin' : ($platform === 'tiktok' ? 'fi fi-brands-tik-tok' : ($platform === 'facebook' ? 'fi fi-brands-facebook' : 'fi fi-rr-share'))) }}"></i>
                </a>
              @endforeach
            @endif
          </div>
        </div>
      </section>

      <!-- Content + Right sidebar (aligned to main page widths) -->
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start" lang="ar">
          <!-- Article -->
          <article class="lg:col-span-2" lang="ar">
            <figure class="rounded-2xl overflow-hidden mb-6 bg-slate-100" style="background-color: #eee;">
              <img src="{{ $post->featured_image_path ? Storage::disk('public')->url($post->featured_image_path) : asset('assets/courses.jpg') }}" alt="Featured" class="max-w-full h-auto object-contain mx-auto" style="max-height: 320px;" />
            </figure>
            <div class="prose prose-slate max-w-none">
                {!! str($post->content)->markdown()->sanitizeHtml() !!}
            </div>
            <!-- Prev/Next navigation -->
            <div class="mt-8 hidden items-center justify-between">
              <a href="#" class="inline-flex items-center gap-3">
                <span class="inline-flex items-center justify-center rounded-full border-2 p-1" style="border-color:#1e3a8a;">
                  <span class="flex items-center justify-center w-9 h-9 rounded-full border-2" style="border-color:#1e3a8a;"><i class="fi fi-rr-arrow-left text-xl"></i></span>
                </span>
                <span class="text-slate-700">Previous</span>
              </a>
              <a href="#" class="inline-flex items-center gap-3">
                <span class="text-slate-700">Next</span>
                <span class="inline-flex items-center justify-center rounded-full border-2 p-1" style="border-color:#1e3a8a;">
                  <span class="flex items-center justify-center w-9 h-9 rounded-full border-2" style="border-color:#1e3a8a;"><i class="fi fi-rr-arrow-right text-xl"></i></span>
                </span>
              </a>
            </div>
          </article>

          <!-- Sidebar -->
          <aside class="lg:col-span-1">
            <div class="rounded-2xl border border-slate-200 bg-white p-5">
              <div class="flex items-center justify-between">
                <h3 class="font-semibold">{{ app()->getLocale()==='ar' ? 'أحدث المقالات' : 'Latest Posts' }}</h3>
                <a href="{{ route('posts.index') }}" class="text-blue-600 text-sm">{{ app()->getLocale()==='ar' ? 'عرض الكل' : 'View all' }}</a>
              </div>
              @php($slug = $post->category->slug ?? null)
              @php($latestPosts = \App\Models\Post::query()
                  ->with('category')
                  ->where('status','published')
                  ->when($slug === 'serve', fn($q) => $q->whereHas('category', fn($c) => $c->where('slug','servers')))
                  ->when($slug !== 'serve', fn($q) => $q->where('category_id', $post->category_id))
                  ->latest('published_at')
                  ->take(5)
                  ->get())
              <ul class="mt-3 space-y-2 text-sm">
                @foreach($latestPosts as $lp)
                  @php($lpTitle = $lp->getTranslation('title', app()->getLocale(), false) ?? $lp->title)
                  @php($lpContent = $lp->getTranslation('content', app()->getLocale(), false))
                  @php($lpText = is_string($lpContent) ? strip_tags($lpContent) : (is_array($lpContent) ? strip_tags(json_encode($lpContent)) : ''))
                  @php($lpMinutes = max(1, (int) ceil(str_word_count($lpText) / 200)))
                  <li>
                    <a href="{{ route('posts.show', $lp->slug) }}" class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-slate-50 border border-transparent hover:border-slate-200">
                      <span>{{ $lpTitle }}</span>
                      <span class="text-xs text-slate-400">{{ $lpMinutes }} {{ app()->getLocale()==='ar' ? 'دقائق' : 'min' }}</span>
                    </a>
                  </li>
                @endforeach
              </ul>
              <hr class="my-5" />
              @php($tagsMeta = $post->meta()->where('meta_key','tags')->first())
              @php($tagsString = $tagsMeta ? $tagsMeta->getTranslation('meta_value', app()->getLocale(), false) : null)
              @php($tags = is_string($tagsString) ? array_filter(array_map('trim', explode(',', $tagsString))) : [])
              @if(count($tags))
                <h4 class="font-semibold">{{ app()->getLocale()==='ar' ? 'الوسوم' : 'Tags' }}</h4>
                <div class="mt-2 flex flex-wrap gap-2">
                  @foreach($tags as $tag)
                    <a href="#" class="px-3 py-1 rounded-full border border-slate-300 text-sm">{{ $tag }}</a>
                  @endforeach
                </div>
              @endif
              <div class="rounded-xl hidden mt-2 bg-primary-blue/10 p-4">
                <h4 class="font-semibold text-primary-blue">Need guidance?</h4>
                <p class="text-sm text-slate-600 mt-1">Book a free session with our advisors.</p>
                <a class="inline-flex items-center mt-3 px-4 py-2 rounded-lg bg-primary-blue text-white" href="index-main.html#contact">Get Free Session</a>
              </div>
            </div>
          </aside>
        </div>
      </div>
    </main>

</x-layout.app>
