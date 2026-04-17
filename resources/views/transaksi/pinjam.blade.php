@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header & Search --}}
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h3 class="fw-bold text-dark mb-1">Katalog Buku</h3>
            <p class="text-muted mb-lg-0">Pilih buku favoritmu dan ajukan peminjaman ke admin.</p>
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

    {{-- Alert Success/Error --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-end mb-4">
        <div class="bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small fw-bold">
            <i class="fas fa-info-circle me-1"></i> Maksimal 3 buku (Termasuk Pending)
        </div>
    </div>

    {{-- Grid Buku --}}
    <div class="row g-4">
        @forelse($bukus as $b)
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden card-buku">
                
                {{-- Bagian Cover Buku --}}
                <div class="position-relative overflow-hidden bg-light" style="height: 240px;">
                    @if(isset($b->cover_url) && $b->cover_url != null)
                        <img src="{{ $b->cover_url }}" class="w-100 h-100 object-fit-cover image-cover" alt="{{ $b->nama_buku }}">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($b->nama_buku) }}&background=random&color=fff&size=512&bold=true&font-size=0.3" 
                             class="w-100 h-100 object-fit-cover image-cover" 
                             alt="{{ $b->nama_buku }}">
                        <div class="book-spine"></div>
                    @endif
                    
                    {{-- Badge Stok --}}
                    <span class="position-absolute top-0 start-0 m-3 badge rounded-pill bg-white text-primary shadow-sm fw-bold badge-stok">
                        Stok: {{ $b->stok }}
                    </span>
                </div>
                
                <div class="card-body p-3 d-flex flex-column">
                    <h6 class="fw-bold text-dark mb-1 text-truncate" title="{{ $b->nama_buku }}">
                        {{ $b->nama_buku }}
                    </h6>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-tag me-1 small"></i>{{ $b->kategori ?? 'Umum' }}
                    </p>
                    
                    <div class="mt-auto">
                        @php
                            // Cek apakah siswa ini sudah meminjam/mengajukan buku ini
                            $isPending = DB::table('transaksi')
                                        ->where('id', auth()->id())
                                        ->where('id_buku', $b->id_buku)
                                        ->whereIn('status', ['Pending', 'Dipinjam'])
                                        ->exists();
                        @endphp

                        @if($isPending)
                            <button class="btn btn-secondary w-100 rounded-pill btn-sm fw-bold py-2 shadow-none" disabled>
                                <i class="fas fa-clock me-1"></i> Sudah Diajukan
                            </button>
                        @else
                            <form action="{{ route('transaksi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_buku" value="{{ $b->id_buku }}">
                                <button type="submit" class="btn btn-primary w-100 rounded-pill btn-sm fw-bold py-2" 
                                        onclick="return confirm('Ajukan peminjaman buku ini?')">
                                    <i class="fas fa-plus-circle me-1"></i> Pinjam
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        {{-- Tampilan Kosong (Tetap Sama) --}}
        <div class="col-12 text-center py-5">
            <div class="mb-3">
                <i class="fas fa-ghost fa-4x text-light"></i>
            </div>
            <h5 class="text-muted fw-bold">Buku tidak ditemukan</h5>
            <a href="{{ route('transaksi.pinjam') }}" class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-2">
                Lihat Semua Buku
            </a>
        </div>
        @endforelse
    </div>
</div>

<style>
    /* Pakai Style kamu yang tadi, tapi tambahkan ini: */
    .btn-secondary:disabled {
        background-color: #e9ecef;
        border-color: #dee2e6;
        color: #6c757d;
        opacity: 0.8;
    }
    
    .alert {
        animation: slideDown 0.5s ease;
    }

    @keyframes slideDown {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    
    /* Sisanya sama dengan CSS asli kamu */
    .card-buku { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid rgba(0,0,0,0.05) !important; }
    .card-buku:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(67, 97, 238, 0.15) !important; border-color: rgba(67, 97, 238, 0.2) !important; }
    .object-fit-cover { object-fit: cover; object-position: center; }
    .image-cover { transition: transform 0.5s ease; }
    .card-buku:hover .image-cover { transform: scale(1.08); }
    .book-spine { position: absolute; top: 0; left: 0; width: 12px; height: 100%; background: linear-gradient(90deg, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0) 100%); z-index: 1; }
    .badge-stok { z-index: 2; font-size: 0.7rem; }
    .btn-primary { background-color: #4361ee; border: none; transition: all 0.3s; }
    .btn-primary:hover { background-color: #3f37c9; transform: scale(1.02); box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3); }
    .input-group:focus-within { border-color: #4361ee !important; box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.1); }
    .text-truncate { max-width: 100%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
</style>
@endsection