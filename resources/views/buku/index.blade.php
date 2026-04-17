@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    {{-- Header & Tombol Aksi --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Manajemen Koleksi Buku</h4>
            <p class="text-muted small">Total: <span class="badge bg-primary rounded-pill">{{ count($bukus) }}</span> Judul Buku</p>
        </div>
        <div class="d-flex gap-2">
            {{-- Tombol Lihat Arsip --}}
            <a href="{{ route('buku.arsip') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-bold shadow-sm bg-white">
                <i class="fas fa-archive me-2"></i>ARSIP
            </a>
            {{-- Tombol Tambah --}}
            <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus-circle me-2"></i>TAMBAH BUKU
            </button>
        </div>
    </div>

    {{-- Filter & Search Box --}}
    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="/admin/buku" method="GET" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0 py-2 shadow-none" 
                               placeholder="Cari judul buku atau ID..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="kondisi" class="form-select bg-light border-0 py-2 shadow-none">
                        <option value="">Semua Kondisi Stok</option>
                        <option value="stok_cukup" {{ request('kondisi') == 'stok_cukup' ? 'selected' : '' }}>Stok Banyak (> 5)</option>
                        <option value="stok_menipis" {{ request('kondisi') == 'stok_menipis' ? 'selected' : '' }}>Stok Menipis (1-5)</option>
                        <option value="stok_habis" {{ request('kondisi') == 'stok_habis' ? 'selected' : '' }}>Stok Habis (0)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2 rounded-3 text-uppercase">Filter</button>
                    <a href="/admin/buku" class="btn btn-outline-secondary py-2 rounded-3 px-3"><i class="fas fa-sync-alt"></i></a>
                </div>
            </form>
        </div>
    </div>

    @if(session('success')) 
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 small">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div> 
    @endif

    {{-- Table Card --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-secondary small fw-bold">
                        <tr>
                            <th class="ps-4 py-3 border-0">KOLEKSI BUKU</th>
                            <th class="border-0">PENERBIT</th>
                            <th class="border-0 text-center">STOK</th>
                            <th class="text-center pe-4 border-0">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bukus as $b)
                        <tr style="border-bottom: 1px solid #f8f9fa;">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="book-icon-box me-3">
                                        <i class="fas fa-book text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0 small">{{ $b->nama_buku }}</div>
                                        <div class="text-muted" style="font-size: 0.7rem;">ID: #{{ $b->id_buku }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-muted small fw-medium">{{ $b->penerbit }}</span>
                            </td>
                            <td class="text-center">
                                @if($b->stok <= 0)
                                    <span class="badge bg-soft-danger text-danger rounded-pill px-3 py-1 fw-bold" style="font-size: 9px; border: 1px solid currentColor;">HABIS</span>
                                @elseif($b->stok <= 5)
                                    <span class="badge bg-soft-warning text-warning rounded-pill px-3 py-1 fw-bold" style="font-size: 9px; border: 1px solid currentColor;">{{ $b->stok }} MENIPIS</span>
                                @else
                                    <span class="badge bg-soft-info text-info rounded-pill px-3 py-1 fw-bold" style="font-size: 9px; border: 1px solid currentColor;">{{ $b->stok }} TERSEDIA</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center gap-1">
                                    <button onclick="editBuku({{ $b->id_buku }})" class="btn btn-sm btn-outline-warning border-0 p-2" data-bs-toggle="modal" data-bs-target="#modalEdit" title="Edit Buku">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="{{ route('buku.delete', $b->id_buku) }}" class="btn btn-sm btn-outline-danger border-0 p-2" onclick="return confirm('Pindahkan buku ke arsip?')" title="Hapus Buku">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-book-open fa-3x mb-3 opacity-25"></i>
                                <p class="small">Koleksi buku tidak ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-plus-circle me-2"></i>Tambah Buku Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('buku.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                        <input type="text" name="nama_buku" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Contoh: Pemrograman Laravel" required>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Penerbit</label>
                            <input type="text" name="penerbit" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Nama Penerbit" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Stok Awal</label>
                            <input type="number" name="stok" class="form-control bg-light border-0 py-2 shadow-none" value="1" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-edit me-2"></i>Update Data Buku</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                        <input type="text" name="nama_buku" id="edit_nama" class="form-control bg-light border-0 py-2 shadow-none" required>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Penerbit</label>
                            <input type="text" name="penerbit" id="edit_penerbit" class="form-control bg-light border-0 py-2 shadow-none" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Stok</label>
                            <input type="number" name="stok" id="edit_stok" class="form-control bg-light border-0 py-2 shadow-none" min="0" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold text-white shadow-sm">UPDATE BUKU</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; }
    .bg-soft-danger { background-color: #fff5f5 !important; color: #e53e3e !important; }
    .bg-soft-warning { background-color: #fffaf0 !important; color: #dd6b20 !important; }
    .bg-soft-info { background-color: #f0f9ff !important; color: #0ea5e9 !important; }
    
    .book-icon-box {
        width: 40px;
        height: 40px;
        background-color: #f0f4ff;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border-color: #4e73df !important;
    }
</style>

<script>
    function editBuku(id) {
        fetch(`/admin/buku/edit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_nama').value = data.nama_buku;
                document.getElementById('edit_penerbit').value = data.penerbit;
                document.getElementById('edit_stok').value = data.stok;
                document.getElementById('formEdit').action = `/admin/buku/update/${id}`;
            });
    }
</script>
@endsection