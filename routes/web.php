<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\HariOperasionalController;
use App\Http\Controllers\JadwalBulananController;
use App\Http\Controllers\LaporanJadwalController;
use App\Http\Controllers\RiwayatJadwalController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Root Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');

});


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

// Halaman login
Route::get('/login', [
    AuthController::class,
    'showLogin',
])->name('login');

// Proses login
Route::post('/login', [
    AuthController::class,
    'login',
])->name('login.process');


/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [
        DashboardController::class,
        'index',
    ])->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    // Master wilayah
    Route::resource(
        'wilayah',
        WilayahController::class
    );

    // Master posyandu
    Route::resource(
        'posyandu',
        PosyanduController::class
    );

    // Master kegiatan
    Route::resource(
        'kegiatan',
        KegiatanController::class
    );

    // Master hari operasional
    Route::resource(
        'hari-operasional',
        HariOperasionalController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Master User - Khusus Admin
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::resource(
                'users',
                UserController::class
            );

        });


    /*
    |--------------------------------------------------------------------------
    | Jadwal Bulanan
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Laporan Jadwal
    |--------------------------------------------------------------------------
    |
    | Route laporan diletakkan sebelum route /jadwal/{jadwal}
    | supaya "laporan" tidak dianggap sebagai ID jadwal.
    |
    */


Route::get('/laporan-jadwal', [
    LaporanJadwalController::class,
    'index',
])->name('laporan-jadwal.index');


Route::get(
    '/riwayat-jadwal',
    [RiwayatJadwalController::class, 'index']
)->name('riwayat-jadwal.index');

    /*
    |--------------------------------------------------------------------------
    | Generate Jadwal
    |--------------------------------------------------------------------------
    |
    | Route khusus diletakkan sebelum route dinamis.
    |
    */

    Route::post('/jadwal/{jadwal}/generate', [
        JadwalBulananController::class,
        'generate',
    ])->name('jadwal.generate');

    Route::post('/jadwal/{jadwal}/generate-one', [
        JadwalBulananController::class,
        'generateOne',
    ])->name('jadwal.generate-one');


    /*
    |--------------------------------------------------------------------------
    | Pengajuan dan Persetujuan Jadwal
    |--------------------------------------------------------------------------
    */

    // Admin mengajukan jadwal
    Route::post('/jadwal/{jadwal}/submit', [
        JadwalBulananController::class,
        'submit',
    ])->name('jadwal.submit');

    // Koordinator menyetujui jadwal
    Route::post('/jadwal/{jadwal}/approve', [
        JadwalBulananController::class,
        'approve',
    ])->name('jadwal.approve');

    // Koordinator menolak jadwal
    Route::post('/jadwal/{jadwal}/reject', [
        JadwalBulananController::class,
        'reject',
    ])->name('jadwal.reject');


    /*
    |--------------------------------------------------------------------------
    | Export PDF
    |--------------------------------------------------------------------------
    */

    Route::get('/jadwal/{jadwal}/pdf', [
        JadwalBulananController::class,
        'pdf',
    ])->name('jadwal.pdf');

    Route::get('/jadwal/{jadwal}/pdf/{posyandu}', [
        JadwalBulananController::class,
        'pdfOne',
    ])->name('jadwal.pdf-one');


    /*
    |--------------------------------------------------------------------------
    | CRUD Jadwal Bulanan
    |--------------------------------------------------------------------------
    */

    // Index
    Route::get('/jadwal', [
        JadwalBulananController::class,
        'index',
    ])->name('jadwal.index');

    // Create
    Route::get('/jadwal/create', [
        JadwalBulananController::class,
        'create',
    ])->name('jadwal.create');

    // Store
    Route::post('/jadwal', [
        JadwalBulananController::class,
        'store',
    ])->name('jadwal.store');

    // Show
    Route::get('/jadwal/{jadwal}', [
        JadwalBulananController::class,
        'show',
    ])->name('jadwal.show');

    // Edit
    Route::get('/jadwal/{jadwal}/edit', [
        JadwalBulananController::class,
        'edit',
    ])->name('jadwal.edit');

    // Update
    Route::put('/jadwal/{jadwal}', [
        JadwalBulananController::class,
        'update',
    ])->name('jadwal.update');

    // Delete
    Route::delete('/jadwal/{jadwal}', [
        JadwalBulananController::class,
        'destroy',
    ])->name('jadwal.destroy');


    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [
        AuthController::class,
        'logout',
    ])->name('logout');

});