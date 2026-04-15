@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">Riwayat Pinjaman</h3>
            <p class="text-muted small">Pantau status peminjaman dan pengembalian buku kamu.</p>
        </div>
        <a href="{{ route('transaksi.pinjam') }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold">
            <i class="fas fa-plus me-1"></i> Pinjam Buku
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-primary bg-opacity-10">
                        <tr>
                            <th class="px-4 py-3 small fw-bold text-primary">BUKU</th>
                            <th class="py-3 small fw-bold text-center text-primary">TGL PINJAM</th>
                            <th class="py-3 small fw-bold text-center text-primary">DEADLINE</th>
                            <th class="py-3 small fw-bold text-center text-primary">STATUS</th>
                            <th class="py-3 small fw-bold text-end text-primary">DENDA</th>
                            <th class="px-4 py-3 small fw-bold text-center text-primary">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($transaksis as $t)
                        <tr>
                            <td class="px-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-3 p-2 me-3 d-none d-sm-block">
                                        <i class="fas fa-book text-secondary"></i>
                                    </div>
                                    <span class="fw-bold text-dark">{{ $t->nama_buku }}</span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="text-muted small">{{ date('d M Y', strtotime($t->tanggal_peminjaman)) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="text-muted small">{{ date('d M Y', strtotime($t->tanggal_pengembalian)) }}</span>
                            </td>
                            <td class="text-center">
                                @if($t->status == 'Dipinjam')
                                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 border border-warning border-opacity-25">
                                        <i class="fas fa-clock me-1 small"></i> Sedang Dipinjam
                                    </span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 border border-success border-opacity-25">
                                        <i class="fas fa-check-circle me-1 small"></i> Dikembalikan
                                    </span>
                                @endif
                            </td>
                            <td class="text-end fw-bold {{ $t->total_denda > 0 ? 'text-danger' : 'text-muted' }}">
                                Rp{{ number_format($t->total_denda, 0, ',', '.') }}
                            </td>
                            <td class="px-4 text-center">
                                @if($t->status == 'Dipinjam')
                                    <button class="btn btn-light btn-sm rounded-pill text-muted border-0" disabled title="Struk tersedia setelah dikembalikan">
                                        <i class="fas fa-print opacity-50"></i>
                                    </button>
                                @else
                                    <a href="{{ route('transaksi.cetak', $t->id_transaksi) }}" 
                                       target="_blank" 
                                       class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold">
                                        <i class="fas fa-print me-1"></i> Struk
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($transaksis->isEmpty())
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/blue/work-from-home.svg" style="width: 150px;" class="mb-3">
                    <p class="text-muted">Kamu belum pernah meminjam buku apapun.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table thead th {
        letter-spacing: 0.5px;
        border-bottom: none;
    }
    .table tbody tr {
        transition: all 0.2s;
    }
    .table tbody tr:hover {
        background-color: #fbfcfe;
    }
    .badge {
        font-weight: 600;
        font-size: 0.75rem;
    }
</style>
@endsection