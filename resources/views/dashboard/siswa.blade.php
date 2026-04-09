@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <div class="card border-0 shadow-sm p-4 bg-white">
                <h2 class="fw-bold text-primary">Halo, {{ auth()->user()->nama }}! 👋</h2>
                <p class="text-muted">Selamat datang di Perpustakaan Digital. Cari buku yang ingin kamu baca hari ini.</p>
                <hr>
                <div class="d-flex gap-3">
                    <a href="/transaksi" class="btn btn-primary px-4 py-2 fw-bold">📖 Cari & Pinjam Buku</a>
                    <a href="/transaksi" class="btn btn-outline-secondary px-4 py-2">📜 Lihat Riwayat Pinjam</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3 mb-3">
                <div class="d-flex align-items-center">
                    <div class="display-6 me-3">🔔</div>
                    <div>
                        <h6 class="mb-0 fw-bold">Info Pengembalian</h6>
                        <small class="text-muted">Pastikan mengembalikan buku tepat waktu agar teman lain bisa meminjam.</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-3 mb-3">
                <div class="d-flex align-items-center">
                    <div class="display-6 me-3">📚</div>
                    <div>
                        <h6 class="mb-0 fw-bold">Koleksi Terbaru</h6>
                        <small class="text-muted">Cek daftar buku secara berkala untuk buku-buku baru.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection