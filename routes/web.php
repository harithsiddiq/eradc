<?php

use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\TrackVisits;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(TrackVisits::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/lang/{locale}', function (string $locale) {
        if (in_array($locale, ['ar', 'en'])) {
            session(['locale' => $locale]);
            app()->setLocale($locale);
        }

        return redirect()->back();
    })->name('lang.switch');

    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AuthController::class, 'register']);
    });

    Route::get('/test', function () {
        return $post = Post::with('meta')->first();
    });

    Route::get('/test-translation', function () {
        return [
            'current_locale' => app()->getLocale(),
            'session_locale' => session('locale'),
            'config_locale' => config('app.locale'),
            'fallback_locale' => config('app.fallback_locale'),
            'translation_explore_courses' => __('explore_courses'),
            'translation_free_consultation' => __('free_consultation'),
            'translation_learn_more' => __('learn_more'),
            'lang_path' => lang_path(),
            'ar_json_exists' => file_exists(lang_path('ar.json')),
            'en_json_exists' => file_exists(lang_path('en.json')),
        ];
    });

    Route::get('/posts', function () {
        $posts = Post::with(['author', 'category'])
            ->whereHas('category', function ($q) {
                $q->whereNull('layout_style')->orWhere('layout_style', null);
            })
            ->latest('created_at')
            ->paginate(12);

        return view('pages.posts', compact('posts'));
    })->name('posts.index');

    Route::get('/post/{slug}', function (string $slug) {
        $post = Post::with(['author', 'category', 'meta'])->where('slug', $slug)->firstOrFail();

        return view('pages.post', compact('post'));
    })->name('posts.show');

    Route::get('/curses', function () {
        $category = Category::where('layout_style', 'training-services')->first();
        $posts = Post::where('category_id', $category?->id)
            ->orWhereIn('category_id', $category?->children->pluck('id') ?? [])
            ->latest()
            ->paginate(12);

        return view('pages.curses', compact('posts', 'category'));
    })->name('curses');

    Route::get('/curse/{slug}', function (string $slug) {
        $post = Post::with(['author', 'category', 'meta'])->where('slug', $slug)->firstOrFail();
        $category = $post->category ? Category::with(['posts', 'children.posts'])->find($post->category_id) : null;

        return view('pages.curse', compact('post', 'category'));
    })->name('curse');

    Route::get('/learn/{course:slug}/{lesson:slug}', [\App\Http\Controllers\LessonController::class, 'show'])->name('lesson.show');
    Route::get('/learn/{course:slug}/{lesson:slug}/video', [\App\Http\Controllers\LessonController::class, 'stream'])->name('lesson.stream');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        if (! $request->user()->hasVerifiedEmail()) {
            $request->fulfill();
        }

        $request->user()->update(['is_active' => true]);

        return redirect()->route('home')->with('verified', true);
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::middleware('verified')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
    });

    Route::post('/learn/{course:slug}/{lesson:slug}/progress', [\App\Http\Controllers\LessonController::class, 'updateProgress'])->name('lesson.progress');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/admin/export-data', [ExportController::class, 'export'])->name('admin.export');
});
