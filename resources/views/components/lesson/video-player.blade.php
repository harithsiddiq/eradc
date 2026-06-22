@props(['lesson', 'course'])

@php
    $provider = $lesson->video_provider;

    // Auto-detect Google Drive regardless of provider
    if (str_contains($lesson->video_url ?? '', 'drive.google.com')) {
        // Match /file/d/ID, id=ID, or open?id=ID
        preg_match('/(?:file\/d\/|id=|open\?id=)([a-zA-Z0-9_-]+)/', $lesson->video_url ?? '', $m);
        $videoId = $m[1] ?? null;
        $videoSrc = $videoId ? "https://drive.google.com/file/d/{$videoId}/preview" : $lesson->video_url;
        $useHtml5 = false;
        $isHls    = false;

    // ── Resolve the correct video source ──────────────────────────
    } elseif ($provider === 'upload') {
        // Use the secure stream route — real file path is never exposed
        $videoSrc = route('lesson.stream', ['course' => $course->slug, 'lesson' => $lesson->slug]);
        $useHtml5  = true;
        $isHls     = false;

    } elseif ($provider === 'direct') {
        $videoSrc = $lesson->video_url;
        $useHtml5  = true;
        $isHls     = false;

    } elseif ($provider === 'hls') {
        $videoSrc = $lesson->video_url;
        $useHtml5  = true;
        $isHls     = true;

    } elseif ($provider === 'youtube' || str_contains($lesson->video_url ?? '', 'youtube.com') || str_contains($lesson->video_url ?? '', 'youtu.be')) {
        // Match standard watch URLs (even with other params), shorts, embed, and youtu.be
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $lesson->video_url ?? '', $m);
        $videoId  = $m[1] ?? null;
        $videoSrc = $videoId ? "https://www.youtube.com/embed/{$videoId}?rel=0&modestbranding=1" : $lesson->video_url;
        $useHtml5  = false;
        $isHls     = false;

    } elseif ($provider === 'vimeo' || str_contains($lesson->video_url ?? '', 'vimeo.com')) {
        preg_match('/vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/', $lesson->video_url ?? '', $m);
        $videoId  = $m[3] ?? (preg_match('/vimeo\.com\/(\d+)/', $lesson->video_url ?? '', $m) ? $m[1] : null);
        $videoSrc = $videoId ? "https://player.vimeo.com/video/{$videoId}" : $lesson->video_url;
        $useHtml5  = false;
        $isHls     = false;
    } else {
        $videoSrc = null;
        $useHtml5  = false;
        $isHls     = false;
    }

    $isEnrolled = auth()->check()
        && \App\Models\Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->where('status', 'active')
            ->exists();
@endphp

{{-- ── Download-prevention wrapper ────────────────────────────────────── --}}
<div
    x-data="videoPlayer({
        progressUrl: '{{ route('lesson.progress', ['course' => $course->slug, 'lesson' => $lesson->slug]) }}',
        csrfToken: '{{ csrf_token() }}',
        isEnrolled: {{ $isEnrolled ? 'true' : 'false' }}
    })"
    class="w-full h-full relative bg-black"
    {{-- Block right-click context menu on the whole player --}}
    @contextmenu.prevent
    style="user-select:none; -webkit-user-select:none;"
>

    @if($videoSrc && $useHtml5)
        {{-- ── HTML5 player (uploaded, direct, HLS) ── --}}
        <video
            x-ref="videoElement"
            controls
            playsinline
            class="w-full h-full object-contain"
            controlsList="nodownload nofullscreen-download"
            disablePictureInPicture
            oncontextmenu="return false;"
            @if($isHls)
                data-hls-url="{{ $videoSrc }}"
            @else
                src="{{ $videoSrc }}"
            @endif
        ></video>

        @if($isHls)
            <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        @endif

        {{-- Mark-complete button for enrolled users --}}
        @if($isEnrolled)
            <div x-show="!completed" style="position:absolute;bottom:16px;right:16px;z-index:10;">
                <button
                    @click="markComplete"
                    style="background:#2563eb;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
                    ✓ Mark as Complete
                </button>
            </div>
            <div x-show="completed" x-cloak style="position:absolute;bottom:16px;right:16px;z-index:10;">
                <span style="background:#16a34a;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;">
                    ✓ Completed
                </span>
            </div>
        @endif

    @elseif($videoSrc && !$useHtml5)
        {{-- ── Iframe player (YouTube / Vimeo / Google Drive) ── --}}
        <iframe
            class="w-full h-full"
            src="{{ $videoSrc }}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen
            referrerpolicy="strict-origin-when-cross-origin"
            style="position:relative;z-index:0;"
        ></iframe>

        @if($isEnrolled)
            <div x-show="!completed" style="position:absolute;bottom:16px;right:16px;z-index:10;">
                <button
                    @click="markComplete"
                    style="background:#2563eb;color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;">
                    ✓ Mark as Complete
                </button>
            </div>
        @endif

    @else
        <div style="display:flex;align-items:center;justify-content:center;height:100%;color:#94a3b8;font-size:14px;">
            No video available for this lesson.
        </div>
    @endif

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('videoPlayer', (config) => ({
            progressUrl: config.progressUrl,
            csrfToken:   config.csrfToken,
            isEnrolled:  config.isEnrolled,
            completed:   false,
            lastSent:    0,

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

                // Progress tracking
                if (this.isEnrolled) {
                    video.addEventListener('timeupdate', this.debounce(() => {
                        if (!video.duration || this.completed) return;
                        const pct = Math.floor((video.currentTime / video.duration) * 100);
                        if (pct >= 80) {
                            this.sendProgress(100);
                            this.completed = true;
                        } else if (pct > this.lastSent + 10) {
                            this.sendProgress(pct);
                            this.lastSent = pct;
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
                }).catch(console.error);
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
