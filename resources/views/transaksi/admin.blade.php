@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold text-dark mb-1">Monitoring Transaksi</h3>
            <p class="text-muted mb-0">Kelola aktivitas sirkulasi dan persetujuan pinjaman.</p>
        </div>
        <div class="col-md-6 text-md-end d-flex justify-content-md-end gap-2 mt-3 mt-md-0">
            {{-- Tombol Lihat Arsip --}}
            <a href="{{ route('transaksi.arsip') }}" class="btn btn-outline-dark shadow-sm rounded-pill px-4 py-2 fw-bold bg-white">
                <i class="fas fa-archive me-2"></i>Lihat Arsip
            </a>

            <a href="{{ route('transaksi.cetak_semua', request()->query()) }}" class="btn btn-success shadow-sm rounded-pill px-4 py-2 fw-bold">
                <i class="fas fa-file-pdf me-2"></i>Cetak Laporan
            </a>

            <button type="button" class="btn btn-primary shadow-sm rounded-pill px-4 py-2 fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus-circle me-2"></i>Tambah Transaksi
            </button>
        </div>
    </div>

    {{-- Filter Terpadu --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form action="{{ url('transaksi') }}" method="GET" class="row g-3">
                <div class="col-lg-12 mb-2">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Cari Siswa / Buku</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0 py-2" placeholder="Ketik nama atau judul buku..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-lg-3">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Tanggal</label>
                    <input type="number" name="tgl" class="form-control bg-light border-0" placeholder="1-31" min="1" max="31" value="{{ request('tgl') }}">
                </div>

                <div class="col-lg-3">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Bulan</label>
                    <select name="bln" class="form-select bg-light border-0 shadow-none">
                        <option value="">Semua Bulan</option>
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ request('bln') == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3">
                    <label class="small fw-bold text-muted mb-1 text-uppercase">Tahun</label>
                    <select name="thn" class="form-select bg-light border-0 shadow-none">
                        <option value="">Semua Tahun</option>
                        @for($y = date('Y'); $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ request('thn') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <div class="col-lg-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2 shadow-sm">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ url('transaksi') }}" class="btn btn-outline-secondary w-100 fw-bold py-2 bg-white">
                        <i class="fas fa-sync me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 small"><i class="fas fa-check-circle me-2"></i> {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 small"><i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}</div> @endif

    {{-- Tabel Transaksi --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive-scroll">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="text-muted fw-bold small text-uppercase">
                            <th class="ps-4 py-3 border-0">Peminjam</th>
                            <th class="py-3 border-0">Buku</th>
                            <th class="py-3 border-0 text-center text-nowrap">Tgl Pinjam</th>
                            <th class="py-3 border-0 text-center">Status</th>
                            <th class="py-3 border-0 text-center text-nowrap">Denda</th>
                            <th class="text-end pe-4 py-3 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($transaksis as $t)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                        {{ strtoupper(substr($t->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-capitalize small">{{ $t->nama }}</div>
                                        <small class="text-muted" style="font-size: 0.7rem;">#{{ $t->id_transaksi }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="text-dark fw-medium text-truncate small" style="max-width: 150px;" title="{{ $t->nama_buku }}">
                                    {{ $t->nama_buku }}
                                </div>
                            </td>
                            <td class="text-center text-nowrap text-muted small">
                                {{ \Carbon\Carbon::parse($t->tanggal_peminjaman)->format('d/m/Y') }}
                            </td>
                            <td class="text-center">
                                @if($t->status == 'Pending')
                                    <span class="badge bg-soft-secondary text-secondary rounded-pill px-3 py-1 border" style="font-size: 0.65rem;">
                                        <i class="fas fa-hourglass-half me-1"></i> PENDING
                                    </span>
                                @elseif($t->status == 'Dipinjam')
                                    <span class="badge bg-soft-warning text-warning rounded-pill px-3 py-1 border" style="font-size: 0.65rem;">
                                        <i class="fas fa-book-reader me-1"></i> DIPINJAM
                                    </span>
                                @else
                                    <span class="badge bg-soft-success text-success rounded-pill px-3 py-1 border" style="font-size: 0.65rem;">
                                        <i class="fas fa-check-circle me-1"></i> KEMBALI
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($t->total_denda > 0)
                                    <span class="{{ $t->status == 'Dikembalikan' ? 'text-muted text-decoration-line-through' : 'text-danger fw-bold' }} small">
                                        Rp{{ number_format($t->total_denda, 0, ',', '.') }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-1">
                                    @if($t->status == 'Pending')
                                        <a href="{{ url('transaksi/edit/'.$t->id_transaksi) }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold shadow-sm" style="font-size: 0.7rem;">
                                            Setujui
                                        </a>
                                    @elseif($t->status == 'Dipinjam')
                                        <a href="{{ url('transaksi/kembali/'.$t->id_transaksi) }}" class="btn btn-sm btn-success rounded-pill px-3 fw-bold shadow-sm" style="font-size: 0.7rem;">
                                            Kembali
                                        </a>
                                    @endif

                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown">
                                            <i class="fas fa-ellipsis-v text-muted"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 small">
                                            <li><a class="dropdown-item" href="{{ route('transaksi.cetak', $t->id_transaksi) }}" target="_blank"><i class="fas fa-print me-2 text-secondary"></i>Cetak Struk</a></li>
                                            <li><a class="dropdown-item" href="{{ url('transaksi/show/'.$t->id_transaksi) }}"><i class="fas fa-eye me-2 text-info"></i>Detail</a></li>
                                            <li><a class="dropdown-item" href="{{ url('transaksi/edit/'.$t->id_transaksi) }}"><i class="fas fa-edit me-2 text-primary"></i>Edit Data</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            {{-- Menu Arsip Baru --}}
                                            <li><a class="dropdown-item text-warning" href="{{ route('transaksi.masukArsip', $t->id_transaksi) }}" onclick="return confirm('Pindahkan data ini ke arsip?')"><i class="fas fa-archive me-2"></i>Arsipkan</a></li>
                                            <li><a class="dropdown-item text-danger" href="{{ url('transaksi/delete/'.$t->id_transaksi) }}" onclick="return confirm('Hapus transaksi ini secara permanen?')"><i class="fas fa-trash me-2"></i>Hapus</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <img src="https://illustrations.popsy.co/gray/box.svg" alt="empty" style="width: 80px;" class="mb-3 opacity-50">
                                <p class="text-muted small mb-0">Tidak ada data transaksi yang ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ url('transaksi/store') }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>Input Pinjaman Baru</h5>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PILIH SISWA</label>
                        <select name="id" class="form-select bg-light border-0 py-2 shadow-none" required>
                            <option value="" hidden>Pilih nama siswa...</option>
                            @foreach($list_anggota as $la)
                                <option value="{{ $la->id }}">{{ $la->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PILIH BUKU</label>
                        <select name="id_buku" class="form-select bg-light border-0 py-2 shadow-none" required>
                            <option value="" hidden>Pilih buku yang tersedia...</option>
                            @foreach($bukus as $b)
                                <option value="{{ $b->id_buku }}">{{ $b->nama_buku }} (Stok: {{ $b->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="alert alert-warning border-0 small rounded-3 mb-0">
                        <i class="fas fa-info-circle me-1"></i> Transaksi akan tersimpan dengan status <strong>Pending</strong>.
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-2 rounded-pill shadow-sm">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-soft-warning { background-color: #fff9db !important; color: #f59f00 !important; border: 1px solid #ffe066 !important; }
    .bg-soft-success { background-color: #ebfbee !important; color: #40c057 !important; border: 1px solid #b2f2bb !important; }
    .bg-soft-secondary { background-color: #f1f3f5 !important; color: #495057 !important; border: 1px solid #dee2e6 !important; }
    
    .table-responsive-scroll {
        max-height: 60vh;
        overflow-y: auto;
    }
    
    .table-responsive-scroll::-webkit-scrollbar { width: 5px; }
    .table-responsive-scroll::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }
    
    .dropdown-item:active { background-color: #4361ee; }
</style>
@endsection