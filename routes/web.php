<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. PUBLIK (Akses Sebelum Login) ---
Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
});

// --- 2. PROTEKSI AUTH (Wajib Login) ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Redirect otomatis ke dashboard sesuai role setelah login
    Route::get('/home', function () {
        return auth()->user()->role == 'admin' 
            ? redirect('/admin/dashboard') 
            : redirect('/siswa/dashboard');
    });

    // --- FITUR TRANSAKSI (Prefix Group) ---
    Route::prefix('transaksi')->group(function () {
        Route::get('/', [TransaksiController::class, 'index']);             // Lihat Semua Data
        Route::get('/show/{id}', [TransaksiController::class, 'show']);      // DETAIL TRANSAKSI (Baru)
        Route::post('/store', [TransaksiController::class, 'store']);       // Pinjam Buku
        Route::get('/edit/{id}', [TransaksiController::class, 'edit']);     // Form Edit
        Route::post('/update/{id}', [TransaksiController::class, 'update']); // Proses Update
        Route::get('/delete/{id}', [TransaksiController::class, 'destroy']); // Hapus Data
        Route::get('/kembali/{id}', [TransaksiController::class, 'kembali']); // Pengembalian
    });

    // --- DASHBOARD KHUSUS SISWA ---
    Route::get('/siswa/dashboard', function () {
        return view('dashboard.siswa');
    });

    // --- KHUSUS AKSES ADMIN (Middleware can:admin-access) ---
    Route::middleware('can:admin-access')->group(function () {
        
        // Dashboard Admin
        Route::get('/admin/dashboard', function () {
            return view('dashboard.admin');
        });

        // CRUD BUKU
        Route::prefix('admin/buku')->group(function () {
            Route::get('/', [BukuController::class, 'index']);
            Route::post('/store', [BukuController::class, 'store']);
            Route::get('/edit/{id}', [BukuController::class, 'edit']); 
            Route::post('/update/{id}', [BukuController::class, 'update']);
            Route::get('/delete/{id}', [BukuController::class, 'destroy']);
        });

        // KELOLA ANGGOTA (SISWA/ADMIN)
        Route::prefix('admin/anggota')->group(function () {
            Route::get('/', [AnggotaController::class, 'index']);
            Route::post('/store', [AnggotaController::class, 'store']);
            Route::get('/edit/{id}', [AnggotaController::class, 'edit']); // Form Edit Anggota
            Route::post('/update/{id}', [AnggotaController::class, 'update']); 
            Route::get('/delete/{id}', [AnggotaController::class, 'destroy']);
        });
    });
});