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
                    'posts' => 'blog'
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

</x-layout.app>
