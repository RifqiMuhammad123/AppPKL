<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\PermintaanController;

// ================= ROOT =================
Route::get('/', function () {
    if (session('auth_role') === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (session('auth_role') === 'guru') {
        return redirect()->route('guru.home');
    }
    return redirect()->route('login');
});

// ================= AUTH =================
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ================= ADMIN =================
Route::prefix('admin')->middleware(['admin.auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Barang (khusus admin)
    Route::prefix('barang')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('admin.barang.index');
        Route::get('/create', [BarangController::class, 'create'])->name('admin.barang.create');
        Route::post('/', [BarangController::class, 'store'])->name('admin.barang.store');
        Route::get('/{id}/edit', [BarangController::class, 'edit'])->name('admin.barang.edit');
        Route::put('/{id}', [BarangController::class, 'update'])->name('admin.barang.update');
        Route::delete('/{id}', [BarangController::class, 'destroy'])->name('admin.barang.destroy');
    });

    // Guru (CRUD data guru)
    Route::prefix('guru')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('admin.guru.index');
        Route::get('/create', [GuruController::class, 'create'])->name('admin.guru.create');
        Route::post('/', [GuruController::class, 'store'])->name('admin.guru.store');
        Route::get('/{id}/edit', [GuruController::class, 'edit'])->name('admin.guru.edit');
        Route::put('/{id}', [GuruController::class, 'update'])->name('admin.guru.update');
        Route::delete('/{id}', [GuruController::class, 'destroy'])->name('admin.guru.destroy');
    });

    // Permintaan dari guru (admin lihat, konfirmasi/tolak)
    Route::prefix('permintaan')->group(function () {
        Route::get('/', [PermintaanController::class, 'adminIndex'])->name('admin.permintaan.index');
        Route::post('/{id}/konfirmasi', [PermintaanController::class, 'konfirmasi'])->name('admin.permintaan.konfirmasi');
        Route::post('/{id}/tolak', [PermintaanController::class, 'tolak'])->name('admin.permintaan.tolak');
    });
});

// ================= GURU =================
Route::prefix('guru')->middleware(['guru.auth'])->group(function () {
    Route::get('/home', fn() => view('dashboard.guru-home'))->name('guru.home');

    // Guru lihat daftar barang (read-only)
    Route::get('/barang', [BarangController::class, 'guruIndex'])->name('guru.barang.index');

    // Guru ajukan permintaan + lihat status
    Route::prefix('permintaan')->group(function () {
        Route::get('/create', [PermintaanController::class, 'create'])->name('guru.permintaan.create');
        Route::post('/', [PermintaanController::class, 'store'])->name('guru.permintaan.store');
        Route::get('/proses', [PermintaanController::class, 'proses'])->name('guru.permintaan.proses');
    });
});

// routes/web.php

Route::prefix('guru')->middleware(['guru.auth'])->group(function () {
    Route::get('/barang', [BarangController::class, 'guruIndex'])->name('guru.barang.index');

    // Ajukan permintaan langsung dari daftar barang
    Route::get('/permintaan/from-barang/{id}', [PermintaanController::class, 'fromBarang'])->name('guru.permintaan.fromBarang');
    Route::post('/permintaan/store-barang/{id}', [PermintaanController::class, 'storeFromBarang'])->name('guru.permintaan.storeFromBarang');
});

// Ajukan permintaan dari daftar barang
Route::get('/permintaan/from-barang/{id}', [PermintaanController::class, 'fromBarang'])->name('guru.permintaan.fromBarang');
Route::post('/permintaan/from-barang/{id}', [PermintaanController::class, 'storeFromBarang'])->name('guru.permintaan.storeFromBarang');


// 🔹 API untuk notifikasi realtime (polling AJAX/fetch)
Route::get('/api/permintaan/notif', [PermintaanController::class, 'cekNotif'])
    ->name('permintaan.notif');

    // 🔹 API untuk guru cek status permintaan
Route::get('/api/guru/permintaan/status', [PermintaanController::class, 'cekStatusGuru'])
    ->name('guru.permintaan.status');

    // Route untuk Admin download PDF
Route::get('/admin/barang/download-pdf', [BarangController::class, 'downloadPdf'])
     ->name('admin.barang.downloadPdf');

     
