<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\TransaksiController;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Transaksi;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. HALAMAN UTAMA ---
Route::get('/', [BukuController::class, 'indexDemo'])->name('katalog');

// --- 2. AKSES GUEST (Login/Register) ---
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/forget-password', [AuthController::class, 'showForgetForm'])->name('password.request');
    Route::post('/forget-password', [AuthController::class, 'resetPasswordLangsung'])->name('password.update.langsung');
});

// --- 3. PROTEKSI AUTH (Sudah Login) ---
Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil User
    Route::get('/profil', [AuthController::class, 'showProfil'])->name('profil');
    Route::post('/profil/update', [AuthController::class, 'updateProfil'])->name('profil.update');

    // Detail Anggota
    Route::get('/admin/anggota/show/{id}', [AnggotaController::class, 'show'])->name('anggota.show');

    // Redirect otomatis sesuai role
    Route::get('/home', function () {
        return auth()->user()->role == 'admin' 
            ? redirect('/admin/dashboard') 
            : redirect('/siswa/dashboard');
    });

    // --- FITUR TRANSAKSI (Siswa & Admin) ---
    Route::prefix('transaksi')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('transaksi.index'); 
        Route::get('/pinjam', [TransaksiController::class, 'pinjamBuku'])->name('transaksi.pinjam');
        Route::get('/riwayat', [TransaksiController::class, 'riwayatBuku'])->name('transaksi.riwayat');
        Route::post('/store', [TransaksiController::class, 'store'])->name('transaksi.store'); 
        Route::get('/show/{id}', [TransaksiController::class, 'show'])->name('transaksi.show');      
        Route::get('/edit/{id}', [TransaksiController::class, 'edit'])->name('transaksi.edit');      
        Route::post('/update/{id}', [TransaksiController::class, 'update'])->name('transaksi.update'); 
        Route::get('/delete/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.delete'); 
        Route::get('/setujui/{id}', [TransaksiController::class, 'setujui'])->name('transaksi.setujui');
        Route::get('/kembali/{id}', [TransaksiController::class, 'kembali'])->name('transaksi.kembali'); 
        Route::get('/cetak/{id}', [TransaksiController::class, 'cetak_pdf'])->name('transaksi.cetak');
        Route::get('/cetak-semua', [TransaksiController::class, 'cetak_semua'])->name('transaksi.cetak_semua');

        // FITUR ARSIP TRANSAKSI
        Route::get('/arsip', [TransaksiController::class, 'arsip'])->name('transaksi.arsip');
        Route::get('/masuk-arsip/{id}', [TransaksiController::class, 'masukArsip'])->name('transaksi.masukArsip');
        Route::get('/restore/{id}', [TransaksiController::class, 'restore'])->name('transaksi.restore');
    });

    // --- DASHBOARD SISWA ---
    Route::get('/siswa/dashboard', function () {
        return view('dashboard.siswa');
    });

    // --- KHUSUS AKSES ADMIN ---
    Route::middleware('can:admin-access')->group(function () {
        
        // Dashboard Admin dengan Statistik Chart
        Route::get('/admin/dashboard', function () {
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

            $bukuMonthly = Buku::selectRaw('COUNT(*) as total, MONTH(created_at) as month')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')->pluck('total', 'month')->all();

            $anggotaMonthly = Anggota::selectRaw('COUNT(*) as total, MONTH(created_at) as month')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')->pluck('total', 'month')->all();

            $transaksiMonthly = Transaksi::selectRaw('COUNT(*) as total, MONTH(created_at) as month')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')->pluck('total', 'month')->all();

            $dataBuku = []; $dataAnggota = []; $dataTransaksi = [];
            for ($i = 1; $i <= 12; $i++) {
                $dataBuku[] = $bukuMonthly[$i] ?? 0;
                $dataAnggota[] = $anggotaMonthly[$i] ?? 0;
                $dataTransaksi[] = $transaksiMonthly[$i] ?? 0;
            }

            return view('dashboard.admin', compact('months', 'dataBuku', 'dataAnggota', 'dataTransaksi'));
        });

        // CRUD BUKU + FITUR ARSIP
        Route::prefix('admin/buku')->group(function () {
            Route::get('/', [BukuController::class, 'index'])->name('buku.index');
            Route::post('/store', [BukuController::class, 'store'])->name('buku.store');
            Route::get('/edit/{id}', [BukuController::class, 'edit'])->name('buku.edit'); 
            Route::post('/update/{id}', [BukuController::class, 'update'])->name('buku.update');
            Route::get('/delete/{id}', [BukuController::class, 'destroy'])->name('buku.delete');
            
            // Fitur Baru: Arsip Buku
            Route::get('/arsip', [BukuController::class, 'arsip'])->name('buku.arsip');
            Route::get('/restore/{id}', [BukuController::class, 'restore'])->name('buku.restore');
            Route::get('/force-delete/{id}', [BukuController::class, 'forceDelete'])->name('buku.forceDelete');
        });

        // KELOLA ANGGOTA
        Route::prefix('admin/anggota')->group(function () {
            Route::get('/', [AnggotaController::class, 'index'])->name('anggota.index');
            Route::post('/store', [AnggotaController::class, 'store'])->name('anggota.store');
            Route::get('/edit/{id}', [AnggotaController::class, 'edit'])->name('anggota.edit');   
            Route::post('/update/{id}', [AnggotaController::class, 'update'])->name('anggota.update'); 
            Route::get('/delete/{id}', [AnggotaController::class, 'destroy'])->name('anggota.delete');
        });
    });
});