@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Riwayat Pinjaman</h4>
            <p class="text-muted small">Pantau status peminjaman dan pengembalian buku kamu</p>
        </div>
        <a href="{{ route('transaksi.pinjam') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
            <i class="fas fa-plus-circle me-2"></i>PINJAM BUKU
        </a>
    </div>

    {{-- Tabel Riwayat --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive-scroll">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-secondary small fw-bold">
                        <tr>
                            <th class="ps-4 py-3 border-0">BUKU</th>
                            <th class="border-0 text-center">TGL PENJAM</th>
                            <th class="border-0 text-center">DEADLINE</th>
                            <th class="border-0 text-center">STATUS</th>
                            <th class="border-0 text-end">DENDA</th>
                            <th class="text-center pe-4 border-0">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $t)
                        <tr style="border-bottom: 1px solid #f8f9fa;">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="book-icon-box me-3">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0 small">{{ $t->nama_buku }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">ID Transaksi: #{{ $t->id_transaksi }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="text-muted small fw-medium">{{ date('d M Y', strtotime($t->tanggal_peminjaman)) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-muted small fw-medium">{{ date('d M Y', strtotime($t->tanggal_pengembalian)) }}</span>
                            </td>
                            <td class="text-center">
                                @if($t->status == 'Pending')
                                    <span class="badge bg-soft-secondary text-secondary rounded-pill px-3 py-1 fw-bold" style="font-size: 9px; border: 1px solid currentColor;">
                                        <i class="fas fa-hourglass-half me-1"></i> PENDING
                                    </span>
                                @elseif($t->status == 'Dipinjam')
                                    <span class="badge bg-soft-warning text-warning rounded-pill px-3 py-1 fw-bold" style="font-size: 9px; border: 1px solid currentColor;">
                                        <i class="fas fa-clock me-1"></i> DIPINJAM
                                    </span>
                                @else
                                    <span class="badge bg-soft-info text-info rounded-pill px-3 py-1 fw-bold" style="font-size: 9px; border: 1px solid currentColor;">
                                        <i class="fas fa-check-circle me-1"></i> DIKEMBALIKAN
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $t->total_denda > 0 ? 'text-danger' : 'text-muted' }} small">
                                Rp{{ number_format($t->total_denda, 0, ',', '.') }}
                            </td>
                            <td class="text-center pe-4">
                                {{-- Aksi Cetak Struk hanya aktif jika sudah dikembalikan --}}
                                @if($t->status == 'Dikembalikan')
                                    <a href="{{ route('transaksi.cetak', $t->id_transaksi) }}" 
                                       target="_blank" 
                                       class="btn btn-sm btn-outline-primary border-0 p-2" 
                                       title="Cetak Struk">
                                        <i class="fas fa-print"></i>
                                    </a>
                                @else
                                    <button class="btn btn-sm btn-outline-secondary border-0 p-2 opacity-50" disabled title="Tersedia setelah buku kembali">
                                        <i class="fas fa-print"></i>
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-history fa-3x mb-3 text-muted opacity-25"></i>
                                    <p class="text-muted small">Kamu belum memiliki riwayat peminjaman buku.</p>
                                    <a href="{{ route('transaksi.pinjam') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4 mt-2">
                                        Mulai Pinjam Sekarang
                                    </a>
                                </div>
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
    body { background-color: #f8f9fc; }
    
    /* Skema warna soft untuk badge status */
    .bg-soft-warning { background-color: #fffaf0 !important; color: #dd6b20 !important; }
    .bg-soft-info { background-color: #f0f9ff !important; color: #0ea5e9 !important; }
    /* Tambahan untuk status Pending */
    .bg-soft-secondary { background-color: #f1f5f9 !important; color: #64748b !important; }

    .book-icon-box {
        width: 38px;
        height: 38px;
        background-color: #f0f4ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }
    .table tbody tr:hover {
        background-color: #fbfcfe;
    }

    .table-responsive-scroll {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .table-responsive-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .table-responsive-scroll::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 10px;
    }
</style>
@endsection