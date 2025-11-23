<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\TrackVisits;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Models\Post;

Route::middleware(TrackVisits::class)->group(function () {
    Route::get('/', [HomeController::class, 'index']);
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
        $posts = Post::first();
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/export-data', [ExportController::class, 'export'])->name('admin.export');
});
