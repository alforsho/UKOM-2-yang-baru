@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 rounded-4 mx-auto" style="max-width: 800px;">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-primary">Detail Transaksi #{{ $transaksi->id_transaksi }}</h5>
            <div class="d-flex gap-2">
                {{-- TOMBOL CETAK STRUK --}}
                <a href="{{ route('transaksi.cetak', $transaksi->id_transaksi) }}" target="_blank" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-print me-1"></i> Cetak Struk
                </a>
                <a href="{{ url('transaksi') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Kembali</a>
            </div>
        </div>
        <div class="card-body p-4">
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
                        <p class="mb-0 small text-muted">Penerbit: {{ $transaksi->penerbit }}</p>
                    </div>
                </div>

                <div class="col-12"><hr class="opacity-25"></div>

                {{-- Status & Tanggal --}}
                <div class="col-md-4 text-center">
                    <label class="text-muted small d-block mb-1 fw-bold">STATUS</label>
                    <span class="badge {{ $transaksi->status == 'Dipinjam' ? 'bg-warning text-dark' : 'bg-success' }} rounded-pill px-4 py-2">
                        {{ strtoupper($transaksi->status) }}
                    </span>
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
                            
                            @if($transaksi->status == 'Dikembalikan')
                                <p class="mb-0">
                                    Denda: <strong class="text-decoration-line-through text-muted">Rp {{ number_format($transaksi->total_denda, 0, ',', '.') }}</strong> 
                                    <span class="badge bg-success ms-2">LUNAS / SELESAI</span>
                                </p>
                            @else
                                <p class="mb-0">Denda Harus Dibayar: <strong class="text-danger">Rp {{ number_format($transaksi->total_denda, 0, ',', '.') }}</strong></p>
                            @endif
                        </div>
                    </div>
                </div>
                @else
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