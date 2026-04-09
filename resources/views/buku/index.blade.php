@extends('layouts.app')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold">Kelola Data Buku</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Buku</button>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">ID</th>
                    <th>Judul Buku</th>
                    <th>Penerbit</th>
                    <th>Stok</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bukus as $b)
                <tr>
                    <td class="ps-3">{{ $b->id_buku }}</td>
                    <td>{{ $b->nama_buku }}</td>
                    <td>{{ $b->penerbit }}</td>
                    <td><span class="badge bg-info">{{ $b->stok }}</span></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning text-white" 
                                onclick="editBuku({{ $b->id_buku }})" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEdit">Edit</button>
                        
                        <a href="/admin/buku/delete/{{ $b->id_buku }}" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirm('Yakin hapus buku ini?')">Hapus</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="/admin/buku/store" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5>Input Buku Baru</h5></div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul Buku</label><input type="text" name="nama_buku" class="form-control" required></div>
                <div class="mb-3"><label>Penerbit</label><input type="text" name="penerbit" class="form-control" required></div>
                <div class="mb-3"><label>Stok</label><input type="number" name="stok" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form id="formEdit" method="POST" class="modal-content">
            @csrf
            <div class="modal-header"><h5>Edit Data Buku</h5></div>
            <div class="modal-body">
                <div class="mb-3"><label>Judul Buku</label><input type="text" name="nama_buku" id="edit_nama" class="form-control" required></div>
                <div class="mb-3"><label>Penerbit</label><input type="text" name="penerbit" id="edit_penerbit" class="form-control" required></div>
                <div class="mb-3"><label>Stok</label><input type="number" name="stok" id="edit_stok" class="form-control" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-warning text-white">Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function editBuku(id) {
        // Ambil data buku via AJAX agar modal terisi otomatis
        fetch(`/admin/buku/edit/${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('edit_nama').value = data.nama_buku;
                document.getElementById('edit_penerbit').value = data.penerbit;
                document.getElementById('edit_stok').value = data.stok;
                // Ubah action form agar mengarah ke route update
                document.getElementById('formEdit').action = `/admin/buku/update/${id}`;
            });
    }
</script>
@endsection