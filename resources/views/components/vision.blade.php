<!-- Our Vision -->
@if (isset($post) && isset($category))
<section id="{{ $category->slug }}" class="py-12 sm:py-16 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center">
      <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-primary-blue mb-6">{{ $category->section_name }}</h3>
      <p class="mt-2 text-gray-600 text-md">
        {!! str($category->description)->markdown()->sanitizeHtml() !!}
      </p>
    </div>

    <div class="py-16 grid sm:grid-cols-2 gap-8">
     @foreach($posts as $singlePost)
      <div class="flex flex-row items-center gap-4 border border-slate-300 rounded-2xl p-4 w-1/2">
        <div>
          <div class="flex flex-col border-l-4 border-brand-600 pl-4 text-center">
            <img src="{{ $singlePost->featured_image_path ? Storage::disk('public')->url($singlePost->featured_image_path) : '' }}" alt="message" style="width: 60px;"/>
            <span class="mt-2 text-2xl text-primary-blue">{{ $singlePost->title }}</span>
          </div>
        </div>
        <div class="border-r border-blue-700 px-2" style="border-right: 2px solid #0071bc">
            {!! str($singlePost->content)->markdown()->sanitizeHtml() !!}
        </div>
      </div>
     @endforeach

    </div>
  </div>
</section>
@endif
