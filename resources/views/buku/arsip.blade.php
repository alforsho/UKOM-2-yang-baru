@extends('layouts.app')

@section('content')
<div class="container py-4 px-4">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-archive me-2 text-secondary"></i>Arsip Koleksi Buku</h3>
            <p class="text-muted mb-0 small">Daftar buku yang sedang dinonaktifkan sementara.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('buku.index') }}" class="btn btn-outline-dark shadow-sm rounded-pill px-4 fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Master Buku
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 small">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-secondary fw-bold small text-uppercase">
                            <th class="ps-4 py-3 border-0">Informasi Buku</th>
                            <th class="py-3 border-0 text-center">Stok Terakhir</th>
                            <th class="text-end pe-4 py-3 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arsip as $b) {{-- Menggunakan variabel $arsip sesuai Controller --}}
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="book-icon-box-muted me-3">
                                        <i class="fas fa-book-dead text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small">{{ $b->nama_buku }}</div>
                                        <small class="text-muted">ID: #{{ $b->id_buku }} | Penerbit: {{ $b->penerbit }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center fw-bold small">
                                <span class="badge bg-light text-dark border">{{ $b->stok }}</span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Tombol Restore --}}
                                    <a href="{{ route('buku.restore', $b->id_buku) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" style="font-size: 0.7rem;">
                                        <i class="fas fa-trash-restore-alt me-1"></i> Pulihkan
                                    </a>
                                    {{-- Tombol Force Delete --}}
                                    <a href="{{ route('buku.forceDelete', $b->id_buku) }}" class="btn btn-sm btn-danger rounded-pill px-3 fw-bold shadow-sm" style="font-size: 0.7rem;" onclick="return confirm('Hapus permanen? Data tidak bisa dikembalikan lagi!')">
                                        <i class="fas fa-times-circle me-1"></i> Hapus Permanen
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted small">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>Arsip buku kosong.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .book-icon-box-muted {
        width: 35px;
        height: 35px;
        background-color: #f8f9fa;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }
</style>
@endsection