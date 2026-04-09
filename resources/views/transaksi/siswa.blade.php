@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary text-white p-3 rounded-circle me-3 shadow-sm">
            <i class="fas fa-book-reader fa-lg"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0 text-dark">Layanan Perpustakaan</h4>
            <p class="text-muted mb-0 small">Pinjam buku dan pantau status pengembalian Anda secara real-time.</p>
        </div>
    </div>

    {{-- Pesan Notifikasi --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        {{-- Sisi Kiri: Form Pinjam --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
                <div class="card-body p-4">
                    <h6 class="fw-bold text-dark mb-4"><i class="fas fa-plus-circle text-primary me-2"></i>Pinjam Buku Baru</h6>
                    <form action="{{ url('transaksi/store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Pilih Koleksi Buku</label>
                            <select name="id_buku" class="form-select border-0 bg-light rounded-3 py-2 shadow-none" required>
                                <option value="" disabled selected>-- Cari & Pilih Buku --</option>
                                @foreach($bukus as $b)
                                    <option value="{{ $b->id_buku }}">{{ $b->nama_buku }} (Stok: {{ $b->stok }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="bg-soft-primary p-3 rounded-4 mb-4">
                            <ul class="small text-primary mb-0 ps-3">
                                <li>Maksimal durasi: <strong>7 Hari</strong></li>
                                <li>Denda: <strong>Rp 1.000 / hari</strong></li>
                                <li>Maksimal pinjam: <strong>3 Buku</strong></li>
                            </ul>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-3 shadow-sm">
                            Pinjam Sekarang <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Sisi Kanan: Riwayat --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="fas fa-history text-primary me-2"></i>Riwayat Pinjaman Saya</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted text-uppercase">
                                <th class="ps-4">Informasi Buku</th>
                                <th class="text-center">Batas Kembali</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Denda</th>
                                <th class="text-center pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksis as $t)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $t->nama_buku }}</div>
                                        <div class="text-muted small">Pinjam: {{ \Carbon\Carbon::parse($t->tanggal_peminjaman)->format('d M Y') }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="small {{ $t->status == 'Dipinjam' ? 'text-danger fw-bold' : 'text-muted' }}">
                                            {{ \Carbon\Carbon::parse($t->tanggal_pengembalian)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($t->status == 'Dipinjam')
                                            <span class="badge bg-soft-warning text-warning rounded-pill px-3 py-2">
                                                <i class="fas fa-clock me-1"></i> Dipinjam
                                            </span>
                                        @else
                                            <span class="badge bg-soft-success text-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check me-1"></i> Kembali
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($t->total_denda > 0)
                                            <div class="text-danger fw-bold small">Rp {{ number_format($t->total_denda, 0, ',', '.') }}</div>
                                            <div class="badge bg-soft-danger text-danger p-1" style="font-size: 9px;">Terlambat</div>
                                        @else
                                            <span class="text-muted small">Rp 0</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        {{-- Siswa hanya bisa hapus riwayat jika buku sudah dikembalikan --}}
                                        @if($t->status == 'Dikembalikan')
                                            <a href="{{ url('transaksi/delete/'.$t->id_transaksi) }}" 
                                               class="btn btn-white btn-sm text-danger shadow-sm rounded-3" 
                                               onclick="return confirm('Hapus riwayat ini?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small"><i class="fas fa-lock me-1"></i> Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <img src="https://illustrations.popsy.co/gray/reading-book.svg" alt="no-data" style="width: 150px;" class="mb-3 d-block mx-auto opacity-50">
                                        <p class="text-muted fw-bold">Belum ada buku yang dipinjam.</p>
                                        <p class="small text-muted">Ayo cari buku menarik dan mulai membaca!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* UI Soft Colors */
    .bg-soft-primary { background-color: #eef2ff; color: #4338ca; }
    .bg-soft-warning { background-color: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .bg-soft-success { background-color: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .bg-soft-danger { background-color: #fef2f2; border: 1px solid #fee2e2; }
    
    .table thead th { font-size: 0.65rem; letter-spacing: 0.1em; padding: 15px 10px; }
    .btn-white { background: #fff; border: 1px solid #edf2f7; }
    .btn-white:hover { background: #f8fafc; border-color: #ef4444; color: #ef4444 !important; }
    .form-select:focus { box-shadow: none; border: 1px solid #0d6efd; }
</style>
@endsection