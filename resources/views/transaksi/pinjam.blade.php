@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h3 class="fw-bold text-dark mb-1">Katalog Buku</h3>
            <p class="text-muted mb-lg-0">Pilih buku favoritmu dan mulai membaca sekarang.</p>
        </div>
        <div class="col-lg-6">
            <form action="{{ route('transaksi.pinjam') }}" method="GET">
                <div class="input-group bg-white shadow-sm rounded-pill p-1 border">
                    <input type="text" name="search" class="form-control border-0 shadow-none px-4 rounded-pill" 
                           placeholder="Cari judul atau kategori buku..." value="{{ request('search') }}">
                    <button class="btn btn-primary rounded-pill px-4" type="submit">
                        <i class="fas fa-search me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <div class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-bold">
            <i class="fas fa-info-circle me-1"></i> Maksimal 3 buku aktif
        </div>
    </div>

    <div class="row g-4">
        @forelse($bukus as $b)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-buku">
                <div class="position-relative bg-light d-flex align-items-center justify-content-center" style="height: 220px;">
                    <i class="fas fa-book fa-4x text-secondary opacity-25"></i>
                    
                    <span class="position-absolute top-0 start-0 m-3 badge rounded-pill bg-white text-primary shadow-sm fw-bold">
                        Stok: {{ $b->stok }}
                    </span>
                </div>
                
                <div class="card-body p-3 d-flex flex-column">
                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $b->nama_buku }}">{{ $b->nama_buku }}</h6>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-tag me-1 small"></i>{{ $b->kategori ?? 'Umum' }}
                    </p>
                    
                    <div class="mt-auto">
                        <form action="{{ route('transaksi.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_buku" value="{{ $b->id_buku }}">
                            <button type="submit" class="btn btn-primary w-100 rounded-pill btn-sm fw-bold py-2">
                                <i class="fas fa-plus-circle me-1"></i> Pinjam
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="fas fa-ghost fa-4x text-light"></i>
            </div>
            <h5 class="text-muted fw-bold">Buku tidak ditemukan</h5>
            <p class="text-muted small">Coba cari dengan kata kunci lain atau periksa stok buku.</p>
            <a href="{{ route('transaksi.pinjam') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-2">
                Lihat Semua Buku
            </a>
        </div>
        @endforelse
    </div>
</div>

<style>
    /* Animasi Card */
    .card-buku {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(0,0,0,0.02) !important;
    }
    .card-buku:hover {
        transform: translateY(-12px);
        box-shadow: 0 20px 40px rgba(67, 97, 238, 0.12) !important;
        border-color: rgba(67, 97, 238, 0.1) !important;
    }

    /* Tombol Style */
    .btn-primary {
        background-color: #4361ee;
        border: none;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        background-color: #3f37c9;
        transform: scale(1.02);
        box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
    }

    /* Search Bar Focus */
    .input-group:focus-within {
        border-color: #4361ee !important;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1);
    }

    /* Utility */
    .text-truncate {
        max-width: 100%;
        display: inline-block;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
</style>
@endsection