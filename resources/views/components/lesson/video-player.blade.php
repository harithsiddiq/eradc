@props(['lesson', 'course'])

@php
    // Convert any YouTube URL format to a proper embed URL
    $videoUrl = $lesson->video_url;

    if ($lesson->video_provider === 'youtube') {
        // Extract the video ID from various YouTube URL formats
        preg_match(
            '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
            $videoUrl,
            $matches
        );
        $videoId = $matches[1] ?? null;
        $videoUrl = $videoId
            ? "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1"
            : $videoUrl;
    }

    if ($lesson->video_provider === 'vimeo') {
        preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches);
        $videoId = $matches[1] ?? null;
        $videoUrl = $videoId
            ? "https://player.vimeo.com/video/{$videoId}"
            : $videoUrl;
    }

    $isEnrolled = auth()->check()
        && \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
@endphp

<div
    x-data="videoPlayer({
        progressUrl: '{{ route('lesson.progress', ['course' => $course->slug, 'lesson' => $lesson->slug]) }}',
        csrfToken: '{{ csrf_token() }}',
        isEnrolled: {{ $isEnrolled ? 'true' : 'false' }}
    })"
    class="w-full h-full relative bg-black"
>

    @if($lesson->video_provider === 'youtube' || $lesson->video_provider === 'vimeo')

        <iframe
            class="w-full h-full"
            src="{{ $videoUrl }}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
        ></iframe>

        @if($isEnrolled)
            <div x-show="!completed" class="absolute bottom-4 right-4 z-10">
                <button
                    @click="markComplete"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-lg text-sm font-semibold transition-colors">
                    ✓ Mark as Complete
                </button>
            </div>
            <div x-show="completed" x-cloak class="absolute bottom-4 right-4 z-10">
                <span class="bg-green-600 text-white px-4 py-2 rounded-lg shadow-lg text-sm font-semibold">
                    ✓ Completed
                </span>
            </div>
        @endif

    @elseif($lesson->video_provider === 'hls' || $lesson->video_provider === 'direct')

        <video
            x-ref="videoElement"
            controls
            class="w-full h-full object-contain"
            @if($lesson->video_provider === 'hls')
                data-hls-url="{{ $videoUrl }}"
            @else
                src="{{ $videoUrl }}"
            @endif
        ></video>

        @if($lesson->video_provider === 'hls')
            <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        @endif

    @else
        <div class="flex items-center justify-center h-full text-gray-400 text-sm">
            No video available for this lesson.
        </div>
    @endif

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('videoPlayer', (config) => ({
            progressUrl: config.progressUrl,
            csrfToken: config.csrfToken,
            isEnrolled: config.isEnrolled,
            completed: false,
            lastSentProgress: 0,

            init() {
                const video = this.$refs.videoElement;
                if (!video) return;

                // HLS setup
                if (video.dataset.hlsUrl) {
                    if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                        const hls = new Hls();
                        hls.loadSource(video.dataset.hlsUrl);
                        hls.attachMedia(video);
                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = video.dataset.hlsUrl;
                    }
                }

                // Progress tracking (only for direct/hls — iframe can't be tracked)
                if (this.isEnrolled) {
                    video.addEventListener('timeupdate', this.debounce(() => {
                        if (!video.duration || this.completed) return;
                        const pct = Math.floor((video.currentTime / video.duration) * 100);
                        if (pct >= 80) {
                            this.sendProgress(100);
                            this.completed = true;
                        } else if (pct > this.lastSentProgress + 10) {
                            this.sendProgress(pct);
                            this.lastSentProgress = pct;
                        }
                    }, 2000));
                }
            },

            markComplete() {
                if (!this.isEnrolled || this.completed) return;
                this.sendProgress(100);
                this.completed = true;
            },

            sendProgress(value) {
                fetch(this.progressUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ progress: value })
                }).catch(err => console.error('Progress error:', err));
            },

            debounce(fn, ms) {
                let t;
                return function (...args) {
                    clearTimeout(t);
                    t = setTimeout(() => fn.apply(this, args), ms);
                };
            }
        }));
    });
</script>
