<x-layout.app>
    @foreach ($posts as $category)
        @if ($category->posts->count())
            @php(
                $base = $category->layout_style)
            @php(
                $component = [
                    'hero' => 'hero',
                    'mission' => 'vision',
                    'vision' => 'vision',
                    'logo-marquee' => 'logo-marquee',
                    'core-materials' => 'levels',
                    'trainers' => 'trainers',
                    'standards' => 'logo-marquee-standards',
                    'training-services' => 'services',
                    'engineering-projects' => 'projects',
                    'featured-projects' => 'infra-projects',
                    'strategic-partner' => 'logo-marquee-partner',
                    'contact-us' => 'contact',
                    'certificates' => 'levels',
                ][$base] ?? $base
            )
            @php(
                $component = file_exists(resource_path('views/components/' . $component . '.blade.php'))
                    ? $component
                    : str_replace('-', '_', $component)
            )
            @includeIf('components.' . $component, [
                'category' => $category,
                'post' => $category->posts->first(),
                'posts' => $category->posts,
            ])
        @endif
    @endforeach
    {{-- <x-hero />
    <x-vision />
    <x-logo-marquee />
    <x-trainers />
    <x-logo-marquee-standards />
    <x-services />
    <x-projects />
    <x-infra-projects />
    <x-contact /> --}}


</x-layout.app>
