<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PosyanduController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\HariOperasionalController;
use App\Http\Controllers\JadwalBulananController;
use App\Http\Controllers\GenerateJadwalController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');


Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Master Data
    |--------------------------------------------------------------------------
    */

    Route::resource('wilayah', WilayahController::class);

    Route::resource('posyandu', PosyanduController::class);

    Route::resource('kegiatan', KegiatanController::class);

    Route::resource(
        'hari-operasional',
        HariOperasionalController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Jadwal Bulanan
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/jadwal/{jadwal}/pdf',
        [JadwalBulananController::class, 'pdf']
    )->name('jadwal.pdf');

    Route::get(
        '/jadwal/{jadwal}/excel',
        [JadwalBulananController::class, 'excel']
    )->name('jadwal.excel');

    Route::post(
        '/jadwal/{jadwal}/generate',
        [JadwalBulananController::class, 'generate']
    )->name('jadwal.generate');

    Route::resource(
        'jadwal',
        JadwalBulananController::class
    );


    /*
    |--------------------------------------------------------------------------
    | Generate Jadwal
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/generate-jadwal',
        [GenerateJadwalController::class, 'index']
    )->name('generate-jadwal.index');

    Route::get(
        '/generate-jadwal/create',
        [GenerateJadwalController::class, 'create']
    )->name('generate-jadwal.create');

    Route::post(
        '/generate-jadwal',
        [GenerateJadwalController::class, 'store']
    )->name('generate-jadwal.store');

    Route::get(
        '/generate-jadwal/{jadwal}',
        [GenerateJadwalController::class, 'show']
    )->name('generate-jadwal.show');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});


Route::get('/', function () {

    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');

});