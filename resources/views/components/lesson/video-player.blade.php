@props(['lesson', 'course'])

<div x-data="videoPlayer({
        progressUrl: '{{ route('lesson.progress', ['course' => $course->slug, 'lesson' => $lesson->slug]) }}',
        csrfToken: '{{ csrf_token() }}',
        isEnrolled: {{ auth()->check() && \App\Models\Enrollment::where('user_id', auth()->id())->where('course_id', $course->id)->where('status', 'active')->exists() ? 'true' : 'false' }}
    })" class="w-full h-full relative group bg-black">

    @if($lesson->video_provider === 'youtube' || $lesson->video_provider === 'vimeo')
        <iframe class="w-full h-full" src="{{ $lesson->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        
        <div x-show="isEnrolled" class="absolute bottom-4 right-4 z-10 transition-opacity" :class="{'opacity-100': !completed, 'opacity-0': completed}">
            <button @click="markComplete" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded shadow-lg text-sm font-medium transition-colors" :disabled="completed">
                <span>{{ __('lesson.mark_complete') ?? 'Mark as Complete' }}</span>
            </button>
        </div>

    @elseif($lesson->video_provider === 'hls' || $lesson->video_provider === 'direct')
        <video 
            x-ref="videoElement"
            controls 
            class="w-full h-full object-contain"
            @if($lesson->video_provider === 'hls')
                data-hls-url="{{ $lesson->video_url }}"
            @else
                src="{{ $lesson->video_url }}"
            @endif
        ></video>
        
        @if($lesson->video_provider === 'hls')
            <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
        @endif
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
                if (this.$refs.videoElement) {
                    const video = this.$refs.videoElement;
                    
                    if (video.dataset.hlsUrl) {
                        if (typeof Hls !== 'undefined' && Hls.isSupported()) {
                            const hls = new Hls();
                            hls.loadSource(video.dataset.hlsUrl);
                            hls.attachMedia(video);
                        } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                            video.src = video.dataset.hlsUrl;
                        }
                    }
                    
                    if (this.isEnrolled) {
                        video.addEventListener('timeupdate', this.debounce(() => {
                            if (!video.duration) return;
                            const progress = Math.floor((video.currentTime / video.duration) * 100);
                            
                            if (progress >= 80 && !this.completed) {
                                this.sendProgress(100);
                                this.completed = true;
                            } else if (progress > this.lastSentProgress + 10 && !this.completed) {
                                this.sendProgress(progress);
                                this.lastSentProgress = progress;
                            }
                        }, 2000));
                    }
                }
            },
            
            markComplete() {
                if (!this.isEnrolled || this.completed) return;
                this.sendProgress(100);
                this.completed = true;
            },
            
            sendProgress(progressValue) {
                fetch(this.progressUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ progress: progressValue })
                }).catch(err => console.error('Error tracking progress:', err));
            },
            
            debounce(func, wait) {
                let timeout;
                return function(...args) {
                    clearTimeout(timeout);
                    timeout = setTimeout(() => func.apply(this, args), wait);
                };
            }
        }));
    });
</script>
