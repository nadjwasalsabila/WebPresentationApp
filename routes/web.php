<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\TutorialDetailController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\ApiController;

// ==========================================
// ROUTE PUBLIK
// ==========================================
Route::get('/',        fn() => redirect()->route('login'));
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================================
// ROUTE TERPROTEKSI
// ==========================================
Route::middleware('auth.check')->group(function () {

    Route::resource('tutorials', TutorialController::class);

    Route::prefix('tutorials/{tutorial}/details')->name('tutorial-details.')->group(function () {
        Route::get('/',              [TutorialDetailController::class, 'index'])->name('index');
        Route::get('/create',        [TutorialDetailController::class, 'create'])->name('create');
        Route::post('/',             [TutorialDetailController::class, 'store'])->name('store');
        Route::get('/{detail}/edit', [TutorialDetailController::class, 'edit'])->name('edit');
        Route::put('/{detail}',      [TutorialDetailController::class, 'update'])->name('update');
        Route::delete('/{detail}',   [TutorialDetailController::class, 'destroy'])->name('destroy');
        Route::post('/{detail}/toggle-status', [TutorialDetailController::class, 'toggleStatus'])->name('toggle-status');
    });

});

// ==========================================
// ROUTE PUBLIK — Presentation & PDF
// ==========================================
Route::get('/presentation/{slug}',       [PresentationController::class, 'show'])->name('presentation.show');
Route::get('/finished/{slug}',           [PresentationController::class, 'finished'])->name('presentation.finished');
Route::get('/api/presentation/{slug}/poll', [PresentationController::class, 'poll'])->name('presentation.poll');

// ==========================================
// WEBSERVICE API — Tanpa autentikasi
// ==========================================
Route::get('/api/{kode_matkul}', [ApiController::class, 'listByMatkul'])
    ->where('kode_matkul', '[A-Za-z0-9.]+'); // Batasi karakter yang valid