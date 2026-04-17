@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 800px;">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-primary">Detail Transaksi #{{ $transaksi->id_transaksi }}</h5>
            <div class="d-flex gap-2">
                {{-- TOMBOL CETAK STRUK: Hanya muncul jika sudah disetujui/dikembalikan --}}
                @if($transaksi->status != 'Pending')
                <a href="{{ route('transaksi.cetak', $transaksi->id_transaksi) }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-print me-1"></i> Cetak Struk
                </a>
                @endif
                <a href="{{ url('transaksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Kembali</a>
            </div>
        </div>
        <div class="card-body p-4">
            {{-- Alert khusus untuk status Pending --}}
            @if($transaksi->status == 'Pending')
            <div class="alert alert-info border-0 rounded-4 mb-4 shadow-sm">
                <i class="fas fa-info-circle me-2"></i> 
                <strong>Status: Menunggu Persetujuan.</strong> 
                Silakan hubungi admin perpustakaan untuk memverifikasi pengambilan buku.
            </div>
            @endif

            <div class="row g-4">
                {{-- Info Peminjam --}}
                <div class="col-md-6">
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">Informasi Peminjam</h6>
                    <div class="p-3 bg-light rounded-3 h-100 border-start border-primary border-4">
                        <p class="mb-1 fw-bold text-dark" style="font-size: 1.1rem;">{{ $transaksi->nama }}</p>
                        <p class="mb-0 small text-muted text-uppercase">ID User: {{ $transaksi->id }}</p>
                    </div>
                </div>
                {{-- Info Buku --}}
                <div class="col-md-6">
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">Informasi Buku</h6>
                    <div class="p-3 bg-light rounded-3 h-100 border-start border-info border-4">
                        <p class="mb-1 fw-bold text-dark" style="font-size: 1.1rem;">{{ $transaksi->nama_buku }}</p>
                        <p class="mb-0 small text-muted">Penerbit: {{ $transaksi->penerbit ?? '-' }}</p>
                    </div>
                </div>

                <div class="col-12"><hr class="opacity-25"></div>

                {{-- Status & Tanggal --}}
                <div class="col-md-4 text-center">
                    <label class="text-muted small d-block mb-1 fw-bold">STATUS</label>
                    @if($transaksi->status == 'Pending')
                        <span class="badge bg-secondary rounded-pill px-4 py-2">PENDING</span>
                    @elseif($transaksi->status == 'Dipinjam')
                        <span class="badge bg-warning text-dark rounded-pill px-4 py-2">DIPINJAM</span>
                    @else
                        <span class="badge bg-success rounded-pill px-4 py-2">DIKEMBALIKAN</span>
                    @endif
                </div>
                <div class="col-md-4 text-center border-start border-end">
                    <label class="text-muted small d-block mb-1 fw-bold">TGL PINJAM</label>
                    <span class="fw-bold text-dark h5">{{ \Carbon\Carbon::parse($transaksi->tanggal_peminjaman)->format('d M Y') }}</span>
                </div>
                <div class="col-md-4 text-center">
                    <label class="text-muted small d-block mb-1 fw-bold text-danger">DEADLINE</label>
                    <span class="fw-bold text-danger h5">{{ \Carbon\Carbon::parse($transaksi->tanggal_pengembalian)->format('d M Y') }}</span>
                </div>

                {{-- Box Denda --}}
                @if($transaksi->total_denda > 0)
                <div class="col-12 mt-4">
                    <div class="alert {{ $transaksi->status == 'Dikembalikan' ? 'alert-success' : 'alert-danger' }} border-0 rounded-4 d-flex align-items-center shadow-sm">
                        <i class="fas {{ $transaksi->status == 'Dikembalikan' ? 'fa-check-circle' : 'fa-clock' }} fa-2x me-3"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Terlambat {{ $transaksi->hari_terlambat }} Hari</h6>
                            <p class="mb-0">
                                @if($transaksi->status == 'Dikembalikan')
                                    Denda: <strong class="text-decoration-line-through text-muted">Rp {{ number_format($transaksi->total_denda, 0, ',', '.') }}</strong> 
                                    <span class="badge bg-success ms-2">LUNAS / SELESAI</span>
                                @else
                                    Denda Harus Dibayar: <strong class="text-danger">Rp {{ number_format($transaksi->total_denda, 0, ',', '.') }}</strong>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @elseif($transaksi->status != 'Pending')
                <div class="col-12 mt-4 text-center">
                    <p class="text-success small mb-0"><i class="fas fa-check me-1"></i> Tidak ada keterlambatan pengembalian.</p>
                </div>
                @endif
            </div>
        </div>
        <div class="card-footer bg-light border-0 p-3 text-center">
            <small class="text-muted">ID Transaksi Database: {{ $transaksi->id_transaksi }} | Update Terakhir: {{ \Carbon\Carbon::parse($transaksi->updated_at)->format('d/m/Y H:i') }}</small>
        </div>
    </div>
</div>
@endsection