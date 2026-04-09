@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Monitoring Transaksi</h3>
            <p class="text-muted mb-0">Kelola aktivitas sirkulasi buku perpustakaan.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button type="button" class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus-circle me-2"></i>Tambah Transaksi
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ url('transaksi') }}" method="GET" class="row g-3">
                <div class="col-lg-5">
                    <input type="text" name="search" class="form-control bg-light border-0 py-2" placeholder="Cari Siswa atau Buku..." value="{{ request('search') }}">
                </div>
                <div class="col-lg-4">
                    <input type="date" name="tanggal" class="form-control bg-light border-0 py-2" value="{{ request('tanggal') }}">
                </div>
                <div class="col-lg-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Filter</button>
                    <a href="{{ url('transaksi') }}" class="btn btn-light border w-100 fw-bold">Reset</a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">{{ session('error') }}</div> @endif

    {{-- Tabel --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted fw-bold small text-uppercase">
                            <th class="ps-4 py-3 border-0">Peminjam</th>
                            <th class="py-3 border-0">Buku</th>
                            <th class="py-3 border-0 text-nowrap">Tgl Pinjam</th>
                            <th class="py-3 border-0 text-nowrap text-danger">Deadline</th>
                            <th class="py-3 border-0 text-center">Status</th>
                            <th class="py-3 border-0 text-center">Denda</th>
                            <th class="text-end pe-4 py-3 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksis as $index => $t)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 40px; height: 40px; min-width: 40px;">
                                        {{ strtoupper(substr($t->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-capitalize">{{ $t->nama }}</div>
                                        <small class="text-muted small">No: {{ $index + 1 }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium" style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $t->nama_buku }}</div>
                            </td>
                            <td class="text-nowrap text-muted small">{{ \Carbon\Carbon::parse($t->tanggal_peminjaman)->format('d/m/Y') }}</td>
                            <td class="text-nowrap small">
                                {{-- Warna deadline berubah merah jika telat dan masih dipinjam --}}
                                <span class="{{ $t->status == 'Dipinjam' && \Carbon\Carbon::now()->gt($t->tanggal_pengembalian) ? 'text-danger fw-bold' : 'text-muted' }}">
                                    {{ \Carbon\Carbon::parse($t->tanggal_pengembalian)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $t->status == 'Dipinjam' ? 'bg-soft-warning text-warning' : 'bg-soft-success text-success' }} rounded-pill px-3 py-2 border" style="font-size: 0.7rem;">
                                    {{ strtoupper($t->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($t->total_denda > 0)
                                    @if($t->status == 'Dikembalikan')
                                        {{-- Denda dicoret jika sudah kembali --}}
                                        <span class="text-muted small text-decoration-line-through">Rp {{ number_format($t->total_denda, 0, ',', '.') }}</span>
                                        <div style="font-size: 0.65rem;" class="text-success fw-bold text-uppercase">Lunas</div>
                                    @else
                                        {{-- Denda merah tegas jika masih dipinjam --}}
                                        <span class="text-danger fw-bold small">Rp {{ number_format($t->total_denda, 0, ',', '.') }}</span>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    <a href="{{ url('transaksi/show/'.$t->id_transaksi) }}" class="btn btn-sm btn-outline-info border-0 rounded-3" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ url('transaksi/edit/'.$t->id_transaksi) }}" class="btn btn-sm btn-outline-primary border-0 rounded-3" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    
                                    @if($t->status == 'Dipinjam')
                                        <a href="{{ url('transaksi/kembali/'.$t->id_transaksi) }}" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm mx-1">
                                            Kembali
                                        </a>
                                    @endif

                                    <a href="{{ url('transaksi/delete/'.$t->id_transaksi) }}" class="btn btn-sm btn-outline-danger border-0 rounded-3" onclick="return confirm('Hapus transaksi ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">Data tidak ditemukan.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-warning { background-color: #fffbeb; color: #d97706; border-color: #fde68a !important; }
    .bg-soft-success { background-color: #f0fdf4; color: #16a34a; border-color: #bbf7d0 !important; }
    .avatar { flex-shrink: 0; }
    .text-decoration-line-through { text-decoration: line-through !important; }
</style>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <form action="{{ url('transaksi/store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold">Input Pinjaman Baru</h5>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">SISWA</label>
                        <select name="user_id" class="form-select bg-light border-0" required>
                            <option value="" hidden>Pilih Siswa...</option>
                            @foreach($list_anggota as $la)
                                <option value="{{ $la->id }}">{{ $la->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">BUKU</label>
                        <select name="id_buku" class="form-select bg-light border-0" required>
                            <option value="" hidden>Pilih Buku...</option>
                            @foreach($bukus as $b)
                                <option value="{{ $b->id_buku }}">{{ $b->nama_buku }} (Stok: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection