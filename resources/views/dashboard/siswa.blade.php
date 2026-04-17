@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-white rounded-4 overflow-hidden position-relative" style="min-height: 250px;">
                
                <div class="position-absolute end-0 bottom-0 p-0 opacity-10 d-none d-md-block" style="transform: translate(10%, 10%); z-index: 0;">
                    <i class="fas fa-book-reader fa-10x text-primary"></i>
                </div>
                
                <div class="card-body p-4 p-md-5 position-relative" style="z-index: 1;">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <h2 class="fw-bold text-primary mb-2">Halo, {{ auth()->user()->nama }}! 👋</h2>
                            <p class="text-muted mb-4 fs-5">Sudahkah kamu membaca hari ini? Temukan koleksi buku menarik dan kelola pinjamanmu dengan mudah.</p>
                            
                            <div class="d-flex flex-wrap gap-3 mb-4">
                                <a href="{{ route('transaksi.pinjam') }}" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm">
                                    <i class="fas fa-plus-circle me-2"></i>Mulai Peminjaman
                                </a>
                                <a href="{{ route('transaksi.riwayat') }}" class="btn btn-outline-primary px-4 py-2 rounded-pill fw-bold border-2">
                                    <i class="fas fa-history me-2"></i>Riwayat Pinjaman
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light border-0 text-center">
                                        <h3 class="fw-bold text-primary mb-0">3</h3>
                                        <small class="text-muted fw-semibold">Limit Buku</small>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center mb-4">
        <h5 class="fw-bold mb-0">Informasi Penting</h5>
        <div class="flex-grow-1 ms-3 hr-light" style="height: 1px; background: #eee;"></div>
    </div>

    <div class="row g-4 text-start">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-2 border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-warning bg-opacity-10 text-warning p-3 rounded-4 me-3">
                            <i class="fas fa-clock fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">Waktu Pengembalian</h6>
                            <p class="mb-0 text-muted small">Maksimal peminjaman adalah <strong>7 hari</strong>. Harap tepat waktu ya!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 p-2 border-start border-info border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-info bg-opacity-10 text-info p-3 rounded-4 me-3">
                            <i class="fas fa-shield-alt fs-3"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-bold text-dark">Aturan Denda</h6>
                            <p class="mb-0 text-muted small">Keterlambatan akan dikenakan denda sebesar <strong>Rp1.000/hari</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Animasi Hover Card */
    .card { transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.02) !important; }
    .card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(67, 97, 238, 0.1) !important; }
    
    /* Hover Button */
    .btn { transition: all 0.3s ease; }
    .btn:hover { transform: translateY(-2px); filter: brightness(1.1); }
    
    .text-primary { color: #4361ee !important; }
    .bg-light { background-color: #f8faff !important; }
</style>
@endsection