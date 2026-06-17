<x-layout.app>
    @if(!$lesson->is_preview)
        @push('head')
            <meta name="robots" content="noindex, nofollow">
        @endpush
    @endif
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ sidebarOpen: false }">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Mobile Sidebar Toggle -->
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden flex items-center gap-2 text-primary-600 font-semibold mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <span>{{ __('lesson.toggle_sidebar') ?? 'Course Content' }}</span>
            </button>

            <!-- Video Player Area -->
            <div class="flex-1 lg:order-last">
                <h1 class="text-3xl font-bold mb-4">{{ $lesson->title }}</h1>
                
                <div class="bg-black rounded-lg overflow-hidden aspect-video shadow-lg mb-6">
                    <x-lesson.video-player :lesson="$lesson" :course="$course" />
                </div>
                
                <div class="prose max-w-none">
                    <h2 class="text-xl font-semibold mb-2">{{ __('lesson.description') ?? 'Description' }}</h2>
                    <p class="text-gray-700 leading-relaxed">{{ $lesson->description }}</p>
                </div>
            </div>

            <!-- Sidebar / Lesson List -->
            <div :class="{'hidden': !sidebarOpen, 'block': sidebarOpen}" class="lg:block w-full lg:w-80 flex-shrink-0 lg:order-first">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden sticky top-8">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-bold text-lg truncate" title="{{ $course->title }}">{{ $course->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $lessons->count() }} {{ __('lesson.lessons_count') ?? 'lessons' }}
                        </p>
                    </div>
                    
                    <ul class="divide-y divide-gray-100 max-h-[calc(100vh-200px)] overflow-y-auto">
                        @foreach($lessons as $sidebarLesson)
                            <li>
                                <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $sidebarLesson->slug]) }}" 
                                   class="block p-4 hover:bg-primary-50 transition-colors {{ $sidebarLesson->id === $lesson->id ? 'bg-primary-50 border-l-4 border-primary-600' : 'border-l-4 border-transparent' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0 pr-4">
                                            <p class="text-sm font-medium text-gray-900 truncate {{ $sidebarLesson->id === $lesson->id ? 'text-primary-700 font-bold' : '' }}">
                                                {{ $sidebarLesson->order }}. {{ $sidebarLesson->title }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1 flex items-center gap-2">
                                                @if($sidebarLesson->duration_seconds)
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    {{ gmdate('i:s', $sidebarLesson->duration_seconds) }}
                                                @endif
                                            </p>
                                        </div>
                                        @if($sidebarLesson->is_preview)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                {{ __('lesson.preview') ?? 'Free' }}
                                            </span>
                                        @elseif(!$isEnrolled)
                                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                        @endif
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            
        </div>
    </div>
</x-layout.app>
