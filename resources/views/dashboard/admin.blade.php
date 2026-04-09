@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-primary">Dashboard Admin</h2>
            <p class="text-muted">Selamat Datang kembali, <strong>{{ auth()->user()->nama }}</strong>. Berikut ringkasan data perpustakaan hari ini.</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-hover h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase">Koleksi Buku</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Buku::count() }}</h2>
                    </div>
                    <div class="icon-circle bg-primary text-white shadow-sm">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
                <hr class="my-3 opacity-25">
                <a href="/admin/buku" class="text-primary small text-decoration-none fw-bold">
                    Kelola Data Buku <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-hover h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase">Total Anggota</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Anggota::count() }}</h2>
                    </div>
                    <div class="icon-circle bg-success text-white shadow-sm">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <hr class="my-3 opacity-25">
                <a href="/admin/anggota" class="text-success small text-decoration-none fw-bold">
                    Lihat Semua Siswa <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-hover h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase">Transaksi Pinjam</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Transaksi::count() }}</h2>
                    </div>
                    <div class="icon-circle bg-warning text-white shadow-sm">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                </div>
                <hr class="my-3 opacity-25">
                <a href="/transaksi" class="text-warning small text-decoration-none fw-bold">
                    Cek Laporan Transaksi <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling agar tampilan lebih premium */
    .card-hover {
        transition: all 0.3s ease;
        border-radius: 15px;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-circle {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endsection