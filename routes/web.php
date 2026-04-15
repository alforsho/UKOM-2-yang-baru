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

    // --- ROUTE PROFIL ---
    Route::get('/profil', [AuthController::class, 'showProfil'])->name('profil');
    Route::post('/profil/update', [AuthController::class, 'updateProfil'])->name('profil.update');

    // Redirect otomatis ke dashboard sesuai role setelah login
    Route::get('/home', function () {
        return auth()->user()->role == 'admin' 
            ? redirect('/admin/dashboard') 
            : redirect('/siswa/dashboard');
    });

    // --- FITUR TRANSAKSI (Prefix Group) ---
    Route::prefix('transaksi')->group(function () {
        // Route Utama (Index untuk Monitoring Admin)
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index'); 
        
        // --- ROUTE KHUSUS SISWA (Katalog & Riwayat) ---
        Route::get('/pinjam', [TransaksiController::class, 'pinjamBuku'])->name('transaksi.pinjam');
        Route::get('/riwayat', [TransaksiController::class, 'riwayatBuku'])->name('transaksi.riwayat');
        
        // --- FITUR OPERASIONAL ---
        Route::post('/store', [TransaksiController::class, 'store'])->name('transaksi.store'); 
        Route::get('/show/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');      
        Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');      
        Route::post('/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update'); 
        Route::get('/delete/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.delete'); 
        Route::get('/kembali/{id}', [TransaksiController::class, 'kembali'])->name('transaksi.kembali'); 
        
        // --- ROUTE CETAK (Penting untuk Filter) ---
        // Struk Satuan
        Route::get('/cetak/{id}', [TransaksiController::class, 'cetak_pdf'])->name('transaksi.cetak');
        // Laporan Gabungan (Bisa All atau Terfilter) -> Output: all.pdf
        Route::get('/cetak-semua', [TransaksiController::class, 'cetak_semua'])->name('transaksi.cetak_semua');
    });

    // --- DASHBOARD KHUSUS SISWA ---
    Route::get('/siswa/dashboard', function () {
        return view('dashboard.siswa');
    });

    // --- KHUSUS AKSES ADMIN ---
    Route::middleware('can:admin-access')->group(function () {
        
        Route::get('/admin/dashboard', function () {
            return view('dashboard.admin');
        });

        // CRUD BUKU
        Route::prefix('admin/buku')->group(function () {
            Route::get('/', [BukuController::class, 'index'])->name('buku.index');
            Route::post('/store', [BukuController::class, 'store'])->name('buku.store');
            Route::get('/edit/{id}', [BukuController::class, 'edit'])->name('buku.edit'); 
            Route::post('/update/{id}', [BukuController::class, 'update'])->name('buku.update');
            Route::get('/delete/{id}', [BukuController::class, 'destroy'])->name('buku.delete');
        });

        // KELOLA ANGGOTA
        Route::prefix('admin/anggota')->group(function () {
            Route::get('/', [AnggotaController::class, 'index'])->name('anggota.index');
            Route::get('/show/{id}', [AnggotaController::class, 'show'])->name('anggota.show');   
            Route::post('/store', [AnggotaController::class, 'store'])->name('anggota.store');
            Route::get('/edit/{id}', [AnggotaController::class, 'edit'])->name('anggota.edit');   
            Route::post('/update/{id}', [AnggotaController::class, 'update'])->name('anggota.update'); 
            Route::get('/delete/{id}', [AnggotaController::class, 'destroy'])->name('anggota.delete');
        });
    });
});