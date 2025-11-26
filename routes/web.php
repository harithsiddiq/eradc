<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TrackVisits;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

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

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
    Route::get('/test', function () {
        return $post = Post::with('meta')->first();
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
        return view('pages.curses');
    })->name('curses');

    Route::get('/curse/{slug}', function (string $slug) {
        $post = Post::with(['author', 'category', 'meta'])->where('slug', $slug)->firstOrFail();
        $category = $post->category ? Category::with(['posts', 'children.posts'])->find($post->category_id) : null;
        return view('pages.curses', compact('post', 'category'));
    })->name('curse');
});

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->intended('/');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware(['throttle:6,1'])->name('verification.send');

    Route::get('/admin/export-data', [ExportController::class, 'export'])->middleware('verified')->name('admin.export');
});
