<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\TutorialDetailController;
use App\Http\Controllers\PresentationController;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| ZONA 1 — AUTH (tidak perlu login, tapi kalau sudah login redirect)
|--------------------------------------------------------------------------
*/
Route::middleware('guest.check')->group(function () {
    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', fn() => redirect()->route('login'));

/*
|--------------------------------------------------------------------------
| ZONA 1 — DASHBOARD DOSEN (wajib login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth.check')->group(function () {

    // CRUD Master Tutorial
    Route::resource('tutorials', TutorialController::class);

    // CRUD Detail Tutorial — nested di bawah tutorial
    Route::prefix('tutorials/{tutorial}/details')
         ->name('tutorial-details.')
         ->group(function () {
             Route::get('/',                        [TutorialDetailController::class, 'index'])->name('index');
             Route::get('/create',                  [TutorialDetailController::class, 'create'])->name('create');
             Route::post('/',                       [TutorialDetailController::class, 'store'])->name('store');
             Route::get('/{detail}/edit',           [TutorialDetailController::class, 'edit'])->name('edit');
             Route::put('/{detail}',                [TutorialDetailController::class, 'update'])->name('update');
             Route::delete('/{detail}',             [TutorialDetailController::class, 'destroy'])->name('destroy');
             Route::post('/{detail}/toggle-status', [TutorialDetailController::class, 'toggleStatus'])->name('toggle-status');
         });
});

/*
|--------------------------------------------------------------------------
| ZONA 2 — PRESENTATION (publik, tanpa login)
| Hanya tampil step dengan status = show
| Auto-refresh via polling
|--------------------------------------------------------------------------
*/
Route::get('/presentation/{slug}', [PresentationController::class, 'show'])
     ->name('presentation.show');

// Endpoint polling AJAX untuk auto-refresh
Route::get('/presentation/{slug}/poll', [PresentationController::class, 'poll'])
     ->name('presentation.poll');

/*
|--------------------------------------------------------------------------
| ZONA 3 — FINISHED PDF (publik, tanpa login)
| Tampil semua step (show + hide) dalam format PDF
|--------------------------------------------------------------------------
*/
Route::get('/finished/{slug}', [PresentationController::class, 'finished'])
     ->name('presentation.finished');

/*
|--------------------------------------------------------------------------
| ZONA 4 — WEBSERVICE API (publik, tanpa auth)
| Untuk sistem luar seperti SiAdin
|--------------------------------------------------------------------------
*/
Route::get('/api/{kode_matkul}', [ApiController::class, 'listByMatkul'])
     ->where('kode_matkul', '[A-Za-z0-9.]+')
     ->name('api.by-matkul');