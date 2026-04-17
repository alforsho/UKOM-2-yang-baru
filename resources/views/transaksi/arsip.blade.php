@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1"><i class="fas fa-trash-restore me-2 text-secondary"></i>Arsip Transaksi</h3>
            <p class="text-muted mb-0">Data transaksi yang telah diarsipkan/dihapus sementara.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ url('transaksi') }}" class="btn btn-outline-dark shadow-sm rounded-pill px-4 fw-bold bg-white">
                <i class="fas fa-arrow-left me-2"></i>Kembali ke Monitoring
            </a>
        </div>
    </div>

    @if(session('success')) 
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 small">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div> 
    @endif

    {{-- Tabel Arsip --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted fw-bold small text-uppercase">
                            <th class="ps-4 py-3 border-0">Peminjam</th>
                            <th class="py-3 border-0">Buku</th>
                            <th class="py-3 border-0 text-center">Tgl Hapus</th>
                            <th class="text-end pe-4 py-3 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($arsip as $t)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3 bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 35px; height: 35px; font-size: 0.8rem;">
                                        {{ strtoupper(substr($t->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark small text-capitalize">{{ $t->nama }}</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">ID: #{{ $t->id_transaksi }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium small text-truncate" style="max-width: 200px;" title="{{ $t->nama_buku }}">
                                    {{ $t->nama_buku }}
                                </div>
                            </td>
                            <td class="text-center text-muted small">
                                {{-- Menggunakan format Carbon yang aman --}}
                                {{ \Carbon\Carbon::parse($t->deleted_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- Tombol Restore --}}
                                    <a href="{{ url('transaksi/restore/'.$t->id_transaksi) }}" class="btn btn-sm btn-info text-white rounded-pill px-3 fw-bold shadow-sm" style="font-size: 0.7rem;">
                                        <i class="fas fa-undo me-1"></i> Restore
                                    </a>
                                    {{-- Tombol Hapus Permanen --}}
                                    <a href="{{ url('transaksi/force-delete/'.$t->id_transaksi) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3 fw-bold" style="font-size: 0.7rem;" onclick="return confirm('Hapus permanen? Data tidak bisa dikembalikan lagi.')">
                                        <i class="fas fa-fire me-1"></i> Permanen
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/box.svg" alt="empty" style="width: 60px;" class="mb-3 opacity-50">
                                <p class="text-muted small mb-0">Tidak ada data di arsip.</p>
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
    /* Tambahan agar hover row terasa lebih halus */
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transition: 0.2s;
    }
</style>
@endsection