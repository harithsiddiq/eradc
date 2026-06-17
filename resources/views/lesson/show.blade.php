<x-layout.app>
    @if(!$lesson->is_preview)
        @push('head')
            <meta name="robots" content="noindex, nofollow">
        @endpush
    @endif

    <div class="min-h-screen bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col lg:flex-row gap-6" x-data="{ sidebarOpen: true }">

                {{-- ─── LEFT: Sidebar Lesson List ─── --}}
                <aside class="w-full lg:w-72 xl:w-80 flex-shrink-0">

                    {{-- Mobile toggle --}}
                    <button
                        @click="sidebarOpen = !sidebarOpen"
                        class="lg:hidden w-full flex items-center justify-between mb-3 p-3 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-700">
                        <span>Course Content</span>
                        <svg class="w-5 h-5 transition-transform" :class="sidebarOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="sidebarOpen" x-cloak class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden lg:sticky lg:top-6">

                        {{-- Course header --}}
                        <div class="p-4 bg-gray-800 text-white">
                            <h3 class="font-bold text-sm leading-tight">{{ $course->title }}</h3>
                            <p class="text-gray-400 text-xs mt-1">{{ $lessons->count() }} lessons</p>
                        </div>

                        {{-- Lesson list --}}
                        <ul class="divide-y divide-gray-100 overflow-y-auto max-h-[70vh]">
                            @foreach($lessons as $sidebarLesson)
                                @php $isCurrent = $sidebarLesson->id === $lesson->id; @endphp
                                <li>
                                    <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $sidebarLesson->slug]) }}"
                                       class="flex items-start gap-3 px-4 py-3 transition-colors
                                              {{ $isCurrent ? 'bg-blue-50 border-l-4 border-blue-600' : 'border-l-4 border-transparent hover:bg-gray-50' }}">

                                        {{-- Number / icon --}}
                                        <span class="flex-shrink-0 mt-0.5 w-6 h-6 rounded-full text-xs flex items-center justify-center font-bold
                                                     {{ $isCurrent ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                                            {{ $sidebarLesson->order }}
                                        </span>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm leading-tight {{ $isCurrent ? 'font-bold text-blue-700' : 'text-gray-800' }}">
                                                {{ $sidebarLesson->title }}
                                            </p>
                                            @if($sidebarLesson->duration_seconds)
                                                <p class="text-xs text-gray-400 mt-0.5">{{ gmdate('i:s', $sidebarLesson->duration_seconds) }}</p>
                                            @endif
                                        </div>

                                        {{-- Badge --}}
                                        @if($sidebarLesson->is_preview)
                                            <span class="flex-shrink-0 text-xs font-semibold text-green-700 bg-green-100 px-2 py-0.5 rounded-full">Free</span>
                                        @elseif(!$isEnrolled)
                                            <svg class="flex-shrink-0 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>

                {{-- ─── RIGHT: Video Player + Info ─── --}}
                <main class="flex-1 min-w-0">

                    {{-- Video player --}}
                    <div class="bg-black rounded-xl overflow-hidden shadow-lg w-full" style="aspect-ratio: 16/9;">
                        <x-lesson.video-player :lesson="$lesson" :course="$course" />
                    </div>

                    {{-- Lesson title & meta --}}
                    <div class="mt-5 bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h1 class="text-2xl font-bold text-gray-900">{{ $lesson->title }}</h1>

                        <div class="flex flex-wrap gap-3 mt-3">
                            @if($lesson->is_preview)
                                <span class="text-xs font-semibold text-green-700 bg-green-100 px-3 py-1 rounded-full">Free Preview</span>
                            @endif
                            @if($lesson->duration_seconds)
                                <span class="text-xs text-gray-500 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ gmdate('i:s', $lesson->duration_seconds) }}
                                </span>
                            @endif
                        </div>

                        @if($lesson->description)
                            <p class="mt-4 text-gray-600 leading-relaxed">{{ $lesson->description }}</p>
                        @endif
                    </div>

                </main>

            </div>
        </div>
    </div>
</x-layout.app>
