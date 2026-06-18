<x-layout.app>

    @push('head')
    <style>
        /* ── Profile Page ──────────────────────────────── */
        .profile-page {
            background: #f1f5f9;
            min-height: calc(100vh - 80px);
            padding: 32px 16px;
        }
        .profile-container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* ── Header card ── */
        .profile-header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 32px;
            display: flex;
            align-items: center;
            gap: 24px;
            margin-bottom: 24px;
            color: #fff;
        }
        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }
        .profile-header__name  { font-size: 22px; font-weight: 700; margin: 0 0 4px; }
        .profile-header__email { font-size: 14px; color: #94a3b8; margin: 0; }

        /* ── Tabs ── */
        .profile-tabs {
            display: flex;
            gap: 4px;
            background: #fff;
            border-radius: 12px;
            padding: 6px;
            margin-bottom: 24px;
            box-shadow: 0 1px 4px rgba(0,0,0,.06);
        }
        .profile-tab {
            flex: 1;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            background: transparent;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
        }
        .profile-tab.active {
            background: #1e293b;
            color: #fff;
        }
        .tab-panel { display: none; }
        .tab-panel.active { display: block; }

        /* ── Course cards ── */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .course-card {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
            transition: transform .2s, box-shadow .2s;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
        }
        .course-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,.12);
        }
        .course-card__thumb {
            width: 100%;
            aspect-ratio: 16/9;
            object-fit: cover;
            background: linear-gradient(135deg, #334155, #1e293b);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .course-card__thumb img { width:100%; height:100%; object-fit:cover; }
        .course-card__thumb-placeholder {
            font-size: 48px;
            opacity: .3;
        }
        .course-card__body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
        .course-card__title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px;
            line-height: 1.35;
        }
        .course-card__meta {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 14px;
        }
        /* Progress bar */
        .progress-wrap { margin-top: auto; }
        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 6px;
        }
        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
        }
        .progress-bar__fill {
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #3b82f6);
            border-radius: 99px;
            transition: width .5s ease;
        }
        .progress-bar__fill--done { background: linear-gradient(90deg, #16a34a, #22c55e); }

        .course-card__btn {
            display: block;
            margin-top: 14px;
            padding: 9px;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s;
        }
        .course-card__btn:hover { background: #1d4ed8; color:#fff; }
        .course-card__btn--done { background: #16a34a; }
        .course-card__btn--done:hover { background: #15803d; }

        /* ── Empty state ── */
        .empty-state {
            text-align: center;
            padding: 64px 24px;
            background: #fff;
            border-radius: 14px;
        }
        .empty-state__icon { font-size: 56px; margin-bottom: 16px; }
        .empty-state__title { font-size: 20px; font-weight: 700; color: #1e293b; margin: 0 0 8px; }
        .empty-state__text  { font-size: 14px; color: #64748b; margin: 0 0 24px; }
        .empty-state__btn {
            display: inline-block;
            padding: 12px 28px;
            background: #2563eb;
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
        }

        /* ── Settings form ── */
        .settings-card {
            background: #fff;
            border-radius: 14px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }
        .settings-card h2 { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0 0 20px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-input {
            width: 100%;
            padding: 10px 14px;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #1e293b;
            transition: border-color .2s;
            box-sizing: border-box;
        }
        .form-input:focus { outline: none; border-color: #2563eb; }
        .form-btn {
            padding: 10px 24px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }
        .form-btn:hover { background: #1d4ed8; }
        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 16px;
        }

        @media (max-width: 640px) {
            .profile-header { flex-direction: column; text-align: center; }
            .courses-grid { grid-template-columns: 1fr; }
        }
    </style>
    @endpush

    <div class="profile-page">
        <div class="profile-container">

            {{-- ── Header ── --}}
            <div class="profile-header">
                <div class="profile-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="profile-header__name">{{ $user->name }}</p>
                    <p class="profile-header__email">{{ $user->email }}</p>
                </div>
            </div>

            {{-- ── Tabs ── --}}
            <div class="profile-tabs">
                <button class="profile-tab active" onclick="switchTab('courses', this)">
                    📚 My Courses ({{ $enrollments->count() }})
                </button>
                <button class="profile-tab" onclick="switchTab('settings', this)">
                    ⚙️ Settings
                </button>
            </div>

            {{-- ── Courses Tab ── --}}
            <div id="tab-courses" class="tab-panel active">
                @if($enrollments->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state__icon">🎓</div>
                        <h3 class="empty-state__title">No courses yet</h3>
                        <p class="empty-state__text">You are not enrolled in any courses yet. Browse our available courses to get started.</p>
                        <a href="{{ route('curses') }}" class="empty-state__btn">Browse Courses</a>
                    </div>
                @else
                    <div class="courses-grid">
                        @foreach($enrollments as $enrollment)
                            @php
                                $course       = $enrollment->course;
                                $totalLessons = $course->lessons->count();
                                $progress     = $enrollment->progress ?? 0;
                                $isDone       = $progress >= 100;

                                // Find the next lesson to continue
                                $nextLesson = $enrollment->lastLesson
                                    ?? $course->lessons->first();
                            @endphp

                            <div class="course-card">
                                {{-- Thumbnail --}}
                                <div class="course-card__thumb">
                                    @if($course->thumbnail_path)
                                        <img src="{{ Storage::url($course->thumbnail_path) }}" alt="{{ $course->title }}">
                                    @else
                                        <span class="course-card__thumb-placeholder">🎬</span>
                                    @endif
                                </div>

                                <div class="course-card__body">
                                    <h3 class="course-card__title">{{ $course->title }}</h3>
                                    <p class="course-card__meta">{{ $totalLessons }} lessons · Enrolled {{ $enrollment->created_at->diffForHumans() }}</p>

                                    {{-- Progress --}}
                                    <div class="progress-wrap">
                                        <div class="progress-label">
                                            <span>{{ $isDone ? '✅ Completed' : 'Progress' }}</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="progress-bar">
                                            <div class="progress-bar__fill {{ $isDone ? 'progress-bar__fill--done' : '' }}"
                                                 style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>

                                    {{-- CTA --}}
                                    @if($nextLesson)
                                        <a href="{{ route('lesson.show', ['course' => $course->slug, 'lesson' => $nextLesson->slug]) }}"
                                           class="course-card__btn {{ $isDone ? 'course-card__btn--done' : '' }}">
                                            {{ $isDone ? '🔁 Watch Again' : ($progress > 0 ? '▶ Continue Learning' : '▶ Start Course') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- ── Settings Tab ── --}}
            <div id="tab-settings" class="tab-panel">
                <div class="settings-card">
                    <h2>🔒 Change Password</h2>

                    @if(session('success'))
                        <div class="alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert-error">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <input type="password" name="password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-input" required>
                        </div>
                        <button type="submit" class="form-btn">Update Password</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(name, btn) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.profile-tab').forEach(b => b.classList.remove('active'));
            document.getElementById('tab-' + name).classList.add('active');
            btn.classList.add('active');
        }

        // Auto-open settings tab if there are errors (password form)
        @if($errors->any())
            window.addEventListener('DOMContentLoaded', () => {
                switchTab('settings', document.querySelectorAll('.profile-tab')[1]);
            });
        @endif
    </script>

</x-layout.app>
