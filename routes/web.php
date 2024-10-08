<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\ReservePaymentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 予約ページ
Route::prefix('reserve')->name('reserve.')->group(function () {
    Route::get('/', [ReserveController::class, 'index'])->name('index');
    Route::post('/checkAuth', [ReserveController::class, 'checkAuth'])->name('checkAuth');
});

Route::middleware('auth')->group(function () {
    // 管理者
    Route::middleware('adminCheck')->prefix('administer')->name('administer.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Administer/Top');
        })->name('top');

        Route::prefix('reserve')->name('reserve.')->group(function () {
            Route::get('/list', [ReserveController::class, 'adminList'])->name('list');
        });
    });

    // ユーザー
    Route::middleware('userCheck')->prefix('user')->name('user.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('User/Top');
        })->name('top');

        // 予約ページ
        Route::prefix('reserve')->name('reserve.')->group(function () {
            Route::get('/create', [ReserveController::class, 'create'])->name('create');
            Route::post('/store', [ReserveController::class, 'store'])->name('store');
            Route::get('/list', [ReserveController::class, 'userList'])->name('list');
            Route::get('/payment', [ReservePaymentController::class, 'payment'])->name('payment');
            Route::post('/cansel', [ReserveController::class, 'cansel'])->name('cansel');
        });
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
