@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-primary text-white py-3 rounded-top-4">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-user-plus me-2"></i>Tambah User/Admin</h5>
                </div>
                <div class="card-body p-4">
                    <form action="/admin/anggota/store" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">ROLE AKSES</label>
                            <select name="role" id="role_select" class="form-select bg-light border-0 py-2" onchange="toggleSiswa()" required>
                                <option value="siswa">Siswa (Anggota)</option>
                                <option value="admin">Admin (Petugas)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                            <input type="text" name="nama" class="form-control bg-light border-0 py-2" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">USERNAME</label>
                            <input type="text" name="username" class="form-control bg-light border-0 py-2" placeholder="Username login" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">PASSWORD</label>
                            <input type="password" name="password" class="form-control bg-light border-0 py-2" placeholder="Password" required>
                        </div>

                        <div id="siswa_fields">
                            <hr class="my-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NIS</label>
                                <input type="text" name="nis" class="form-control bg-light border-0 py-2" placeholder="Nomor Induk Siswa">
                            </div>
                            <div class="row">
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">KELAS</label>
                                    <select name="kelas" class="form-select bg-light border-0 py-2">
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>
                                </div>
                                <div class="col-6 mb-3">
                                    <label class="form-label fw-bold small text-muted">JURUSAN</label>
                                    <select name="jurusan" class="form-select bg-light border-0 py-2">
                                        <option value="RPL">RPL</option>
                                        <option value="TKJ">TKJ</option>
                                        <option value="MM">MM</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">NO. TELP</label>
                                <input type="text" name="no_telp" class="form-control bg-light border-0 py-2" placeholder="08xxx">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mt-3 rounded-3 shadow-sm">
                            <i class="fas fa-save me-2"></i>SIMPAN DATA
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-dark">Daftar Pengguna</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light text-secondary small fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">NAMA / USER</th>
                                    <th>ROLE</th>
                                    <th>INFO</th>
                                    <th class="text-center pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $u)
                                <tr>
                                    <td class="ps-4">
                                        <div class="fw-bold text-dark">{{ $u->nama }}</div>
                                        <div class="text-muted small">@ {{ $u->username }}</div>
                                    </td>
                                    <td>
                                        <span class="badge {{ $u->role == 'admin' ? 'bg-soft-danger text-danger' : 'bg-soft-info text-info' }} rounded-pill px-3">
                                            {{ strtoupper($u->role) }}
                                        </span>
                                    </td>
                                    <td class="small">
                                        @if($u->role == 'siswa')
                                            <span class="text-dark fw-medium">{{ $u->nis }}</span> 
                                            <span class="text-muted">({{ $u->kelas }}-{{ $u->jurusan }})</span>
                                        @else
                                            <span class="text-muted italic">Administrator</span>
                                        @endif
                                    </td>
                                    <td class="text-center pe-4">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-white text-warning border" 
                                                    onclick="editUser({{ json_encode($u) }})" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#modalEdit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="/admin/anggota/delete/{{ $u->id }}" 
                                               class="btn btn-sm btn-white text-danger border" 
                                               onclick="return confirm('Hapus user ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" action="" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">ROLE AKSES</label>
                        <select name="role" id="edit_role" class="form-select bg-light border-0" onchange="toggleEditSiswa()" required>
                            <option value="siswa">Siswa (Anggota)</option>
                            <option value="admin">Admin (Petugas)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">USERNAME</label>
                        <input type="text" name="username" id="edit_username" class="form-control bg-light border-0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">PASSWORD <small class="fw-normal">(Kosongkan jika tidak ganti)</small></label>
                        <input type="password" name="password" class="form-control bg-light border-0">
                    </div>

                    <div id="edit_siswa_fields">
                        <hr class="my-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">NIS</label>
                            <input type="text" name="nis" id="edit_nis" class="form-control bg-light border-0">
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">KELAS</label>
                                <select name="kelas" id="edit_kelas" class="form-select bg-light border-0">
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">JURUSAN</label>
                                <select name="jurusan" id="edit_jurusan" class="form-select bg-light border-0">
                                    <option value="RPL">RPL</option>
                                    <option value="TKJ">TKJ</option>
                                    <option value="MM">MM</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">NO. TELP</label>
                            <input type="text" name="no_telp" id="edit_no_telp" class="form-control bg-light border-0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-soft-danger { background-color: #fee2e2; color: #dc2626; }
    .bg-soft-info { background-color: #e0f2fe; color: #0284c7; }
    .btn-white { background: #fff; }
    .btn-white:hover { background: #f8f9fa; }
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
        border: 1px solid #0d6efd !important;
    }
</style>

<script>
    // Toggle fields untuk form Tambah
    function toggleSiswa() {
        var role = document.getElementById('role_select').value;
        var fields = document.getElementById('siswa_fields');
        fields.style.display = (role === 'admin') ? 'none' : 'block';
    }

    // Toggle fields untuk form Edit
    function toggleEditSiswa() {
        var role = document.getElementById('edit_role').value;
        var fields = document.getElementById('edit_siswa_fields');
        fields.style.display = (role === 'admin') ? 'none' : 'block';
    }

    // Fungsi isi data ke Modal Edit
    function editUser(user) {
        document.getElementById('formEdit').action = "/admin/anggota/update/" + user.id;
        document.getElementById('edit_nama').value = user.nama;
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_nis').value = user.nis || '';
        document.getElementById('edit_kelas').value = user.kelas || '10';
        document.getElementById('edit_jurusan').value = user.jurusan || 'RPL';
        document.getElementById('edit_no_telp').value = user.no_telp || '';
        
        toggleEditSiswa();
    }

    // Inisialisasi awal
    document.addEventListener('DOMContentLoaded', function() {
        toggleSiswa();
    });
</script>
@endsection