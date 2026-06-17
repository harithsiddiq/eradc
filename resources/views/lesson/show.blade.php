<x-layout.app>
    @if(!$lesson->is_preview)
        @push('head')
            <meta name="robots" content="noindex, nofollow">
        @endpush
    @endif

    @push('head')
    <style>
        .lesson-page {
            background: #f8fafc;
            min-height: calc(100vh - 80px);
            padding: 24px 16px;
        }
        .lesson-container {
            max-width: 1280px;
            margin: 0 auto;
            display: flex;
            flex-direction: row;
            gap: 24px;
            align-items: flex-start;
        }

        /* ── Sidebar ── */
        .lesson-sidebar {
            width: 300px;
            min-width: 260px;
            flex-shrink: 0;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            position: sticky;
            top: 24px;
            max-height: calc(100vh - 48px);
            display: flex;
            flex-direction: column;
        }
        .lesson-sidebar__header {
            background: #1e293b;
            color: #fff;
            padding: 16px;
            flex-shrink: 0;
        }
        .lesson-sidebar__title {
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            margin: 0 0 4px;
        }
        .lesson-sidebar__count {
            font-size: 12px;
            color: #94a3b8;
            margin: 0;
        }
        .lesson-sidebar__list {
            list-style: none;
            margin: 0;
            padding: 0;
            overflow-y: auto;
            flex: 1;
        }
        .lesson-sidebar__item a {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            border-left: 3px solid transparent;
            transition: background 0.15s;
            border-bottom: 1px solid #f1f5f9;
        }
        .lesson-sidebar__item a:hover { background: #f8fafc; }
        .lesson-sidebar__item--active a {
            background: #eff6ff;
            border-left-color: #2563eb;
        }
        .lesson-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e2e8f0;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }
        .lesson-sidebar__item--active .lesson-num {
            background: #2563eb;
            color: #fff;
        }
        .lesson-meta { flex: 1; min-width: 0; }
        .lesson-meta__title {
            font-size: 13px;
            color: #1e293b;
            font-weight: 500;
            line-height: 1.35;
            margin: 0 0 3px;
            word-break: break-word;
        }
        .lesson-sidebar__item--active .lesson-meta__title {
            color: #1d4ed8;
            font-weight: 700;
        }
        .lesson-meta__duration {
            font-size: 11px;
            color: #94a3b8;
            margin: 0;
        }
        .lesson-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 99px;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .lesson-badge--free { background: #dcfce7; color: #15803d; }
        .lesson-lock { color: #94a3b8; flex-shrink: 0; width: 16px; height: 16px; }

        /* ── Main video area ── */
        .lesson-main { flex: 1; min-width: 0; }
        .lesson-video-wrap {
            width: 100%;
            aspect-ratio: 16 / 9;
            background: #000;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 24px rgba(0,0,0,.18);
        }
        .lesson-video-wrap iframe,
        .lesson-video-wrap video {
            width: 100%;
            height: 100%;
            display: block;
            border: none;
        }
        .lesson-info {
            margin-top: 16px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            padding: 20px;
        }
        .lesson-info__title {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 12px;
        }
        .lesson-info__pills {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 12px;
        }
        .lesson-info__desc {
            font-size: 14px;
            color: #475569;
            line-height: 1.7;
            margin: 0;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .lesson-container { flex-direction: column; }
            .lesson-sidebar {
                width: 100%;
                min-width: unset;
                position: static;
                max-height: 320px;
            }
        }
    </style>
    @endpush

    <div class="lesson-page">
        <div class="lesson-container">

            {{-- ── Sidebar ── --}}
            <aside class="lesson-sidebar">
                <div class="lesson-sidebar__header">
                    <p class="lesson-sidebar__title">{{ $course->title }}</p>
                    <p class="lesson-sidebar__count">{{ $lessons->count() }} lessons</p>
                </div>
                <ul class="lesson-sidebar__list">
                    @foreach($lessons as $sidebarLesson)
                        @php $isCurrent = $sidebarLesson->id === $lesson->id; @endphp
                        <li class="lesson-sidebar__item {{ $isCurrent ? 'lesson-sidebar__item--active' : '' }}">
                            <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $sidebarLesson->slug]) }}">
                                <span class="lesson-num">{{ $sidebarLesson->order }}</span>
                                <div class="lesson-meta">
                                    <p class="lesson-meta__title">{{ $sidebarLesson->title }}</p>
                                    @if($sidebarLesson->duration_seconds)
                                        <p class="lesson-meta__duration">{{ gmdate('i:s', $sidebarLesson->duration_seconds) }}</p>
                                    @endif
                                </div>
                                @if($sidebarLesson->is_preview)
                                    <span class="lesson-badge lesson-badge--free">Free</span>
                                @elseif(!$isEnrolled)
                                    <svg class="lesson-lock" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                @endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            </aside>

            {{-- ── Video + Info ── --}}
            <main class="lesson-main">
                <div class="lesson-video-wrap">
                    <x-lesson.video-player :lesson="$lesson" :course="$course" />
                </div>
                <div class="lesson-info">
                    <h1 class="lesson-info__title">{{ $lesson->title }}</h1>
                    <div class="lesson-info__pills">
                        @if($lesson->is_preview)
                            <span class="lesson-badge lesson-badge--free">Free Preview</span>
                        @endif
                        @if($lesson->duration_seconds)
                            <span style="font-size:12px;color:#64748b;">⏱ {{ gmdate('i:s', $lesson->duration_seconds) }}</span>
                        @endif
                    </div>
                    @if($lesson->description)
                        <p class="lesson-info__desc">{{ $lesson->description }}</p>
                    @endif
                </div>
            </main>

        </div>
    </div>
</x-layout.app>
