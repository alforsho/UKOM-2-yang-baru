@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- Header & Tombol Tambah --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Manajemen Koleksi Buku</h4>
            <p class="text-muted small mb-0">Total: {{ count($bukus) }} Judul Buku</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus me-2"></i>Tambah Buku
        </button>
    </div>

    {{-- Filter & Search Box --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="/admin/buku" method="GET" class="row g-2">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari judul buku..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="kondisi" class="form-select bg-light border-0">
                        <option value="">Semua Kondisi Stok</option>
                        <option value="stok_cukup" {{ request('kondisi') == 'stok_cukup' ? 'selected' : '' }}>Stok Banyak (> 5)</option>
                        <option value="stok_menipis" {{ request('kondisi') == 'stok_menipis' ? 'selected' : '' }}>Stok Menipis (1-5)</option>
                        <option value="stok_habis" {{ request('kondisi') == 'stok_habis' ? 'selected' : '' }}>Stok Habis (0)</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark w-100 rounded-pill">Filter</button>
                    <a href="/admin/buku" class="btn btn-outline-secondary rounded-pill"><i class="fas fa-sync"></i></a>
                </div>
            </form>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-uppercase small fw-bold">
                    <tr>
                        <th class="ps-4 py-3">Buku</th>
                        <th>Penerbit</th>
                        <th>Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $b)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-2 me-3">
                                    <i class="fas fa-book fa-lg"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark">{{ $b->nama_buku }}</div>
                                    <small class="text-muted">ID: #{{ $b->id_buku }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $b->penerbit }}</td>
                        <td>
                            @if($b->stok <= 0)
                                <span class="badge bg-danger rounded-pill px-3">Habis</span>
                            @elseif($b->stok <= 5)
                                <span class="badge bg-warning text-dark rounded-pill px-3">Menipis: {{ $b->stok }}</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $b->stok }} Tersedia</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                <button onclick="editBuku({{ $b->id_buku }})" class="btn btn-white btn-sm border-end" data-bs-toggle="modal" data-bs-target="#modalEdit">
                                    <i class="fas fa-edit text-warning"></i>
                                </button>
                                <a href="/admin/buku/delete/{{ $b->id_buku }}" class="btn btn-white btn-sm" onclick="return confirm('Hapus buku ini?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">Buku tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="/admin/buku/store" method="POST" class="modal-content border-0 rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold mt-2">Tambah Buku Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3"><label class="form-label small fw-bold">Judul Buku</label><input type="text" name="nama_buku" class="form-control bg-light border-0" required></div>
                <div class="row">
                    <div class="col-8"><div class="mb-3"><label class="form-label small fw-bold">Penerbit</label><input type="text" name="penerbit" class="form-control bg-light border-0" required></div></div>
                    <div class="col-4"><div class="mb-3"><label class="form-label small fw-bold">Stok</label><input type="number" name="stok" class="form-control bg-light border-0" value="1" required></div></div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="submit" class="btn btn-primary rounded-pill px-5">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEdit" method="POST" class="modal-content border-0 rounded-4">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold mt-2">Update Data Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3"><label class="form-label small fw-bold">Judul Buku</label><input type="text" name="nama_buku" id="edit_nama" class="form-control bg-light border-0" required></div>
                <div class="row">
                    <div class="col-8"><div class="mb-3"><label class="form-label small fw-bold">Penerbit</label><input type="text" name="penerbit" id="edit_penerbit" class="form-control bg-light border-0" required></div></div>
                    <div class="col-4"><div class="mb-3"><label class="form-label small fw-bold">Stok</label><input type="number" name="stok" id="edit_stok" class="form-control bg-light border-0" required></div></div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="submit" class="btn btn-warning text-white rounded-pill px-5">Update</button>
            </div>
        </form>
    </div>
</div>

<style>
    .btn-white { background: #fff; }
    .form-control:focus, .form-select:focus { box-shadow: none; border: 1px solid #0d6efd; background-color: #fff !important; }
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