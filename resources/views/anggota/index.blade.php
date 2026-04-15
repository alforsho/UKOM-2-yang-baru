@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">Manajemen Pengguna</h4>
            <p class="text-muted small">Kelola data admin dan siswa perpustakaan</p>
        </div>
        <button class="btn btn-primary rounded-3 px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fas fa-plus-circle me-2"></i>TAMBAH USER
        </button>
    </div>

    <div class="card shadow-sm border-0 rounded-4 mb-4">
        <div class="card-body p-3">
            <form action="/admin/anggota" method="GET" class="row g-2 align-items-center">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-0 py-2 shadow-none" 
                               placeholder="Cari nama, username, atau NIS..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select bg-light border-0 py-2 shadow-none">
                        <option value="">Semua Role</option>
                        <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-dark w-100 fw-bold py-2 rounded-3 text-uppercase">Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-secondary small fw-bold">
                        <tr>
                            <th class="ps-4 py-3 border-0">PENGGUNA</th>
                            <th class="border-0 text-center">ROLE</th>
                            <th class="border-0">KETERANGAN</th>
                            <th class="text-center pe-4 border-0">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr style="border-bottom: 1px solid #f8f9fa;">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-box me-3 {{ $u->role == 'admin' ? 'bg-primary' : 'bg-info' }}">
                                        {{ strtoupper(substr($u->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0">{{ $u->nama }}</div>
                                        <div class="text-muted small">@ {{ $u->username }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $u->role == 'admin' ? 'bg-soft-danger text-danger' : 'bg-soft-info text-info' }} rounded-pill px-3 py-2 fw-bold" style="font-size: 10px;">
                                    {{ strtoupper($u->role) }}
                                </span>
                            </td>
                            <td class="small">
                                @if($u->role == 'siswa')
                                    <div class="fw-bold text-dark">{{ $u->nis }}</div> 
                                    <div class="text-muted" style="font-size: 11px;">{{ $u->kelas }} {{ $u->jurusan }}</div>
                                @else
                                    <span class="text-muted opacity-50 italic">Administrator System</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                <div class="btn-group gap-1">
                                    <a href="/admin/anggota/show/{{ $u->id }}" class="btn btn-sm btn-outline-info border-0 p-2" title="Detail & Cetak">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-sm btn-outline-warning border-0 p-2" onclick="editUser({{ json_encode($u) }})" data-bs-toggle="modal" data-bs-target="#modalEdit" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <a href="/admin/anggota/delete/{{ $u->id }}" class="btn btn-sm btn-outline-danger border-0 p-2" onclick="return confirm('Hapus user?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                <p class="small">Tidak ada data pengguna yang ditemukan</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-primary text-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-plus me-2"></i>Tambah User Baru</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/anggota/store" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">ROLE AKSES</label>
                        <select name="role" id="role_select" class="form-select bg-light border-0 py-2 shadow-none" onchange="toggleSiswa()" required>
                            <option value="siswa">Siswa (Anggota)</option>
                            <option value="admin">Admin (Petugas)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
                        <input type="text" name="nama" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Masukkan Nama Lengkap" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">USERNAME</label>
                            <input type="text" name="username" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Username login" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">PASSWORD</label>
                            <input type="password" name="password" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Password" required>
                        </div>
                    </div>

                    <div id="siswa_fields">
                        <hr class="my-3 opacity-50">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">NIS</label>
                            <input type="text" name="nis" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Nomor Induk Siswa">
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">KELAS</label>
                                <select name="kelas" class="form-select bg-light border-0 py-2 shadow-none">
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">JURUSAN</label>
                                <select name="jurusan" class="form-select bg-light border-0 py-2 shadow-none">
                                    <option value="RPL">RPL</option>
                                    <option value="TKJ">TKJ</option>
                                    <option value="MM">MM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4 fw-bold">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header bg-warning text-white border-0 py-3 px-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-edit me-2"></i>Update Pengguna</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEdit" action="" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">ROLE AKSES</label>
                        <select name="role" id="edit_role" class="form-select bg-light border-0 py-2 shadow-none" onchange="toggleEditSiswa()" required>
                            <option value="siswa">Siswa (Anggota)</option>
                            <option value="admin">Admin (Petugas)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">NAMA LENGKAP</label>
                        <input type="text" name="nama" id="edit_nama" class="form-control bg-light border-0 py-2 shadow-none" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">USERNAME</label>
                            <input type="text" name="username" id="edit_username" class="form-control bg-light border-0 py-2 shadow-none" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">GANTI PASSWORD</label>
                            <input type="password" name="password" class="form-control bg-light border-0 py-2 shadow-none" placeholder="Kosongkan jika tidak ganti">
                        </div>
                    </div>

                    <div id="edit_siswa_fields">
                        <hr class="my-3 opacity-50">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">NIS</label>
                            <input type="text" name="nis" id="edit_nis" class="form-control bg-light border-0 py-2 shadow-none">
                        </div>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">KELAS</label>
                                <select name="kelas" id="edit_kelas" class="form-select bg-light border-0 py-2 shadow-none">
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <div class="col-6 mb-3">
                                <label class="form-label small fw-bold text-muted">JURUSAN</label>
                                <select name="jurusan" id="edit_jurusan" class="form-select bg-light border-0 py-2 shadow-none">
                                    <option value="RPL">RPL</option>
                                    <option value="TKJ">TKJ</option>
                                    <option value="MM">MM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-3 px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning rounded-3 px-4 fw-bold text-white">UPDATE DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    body { background-color: #f8f9fc; font-family: 'Inter', sans-serif; }
    .bg-soft-danger { background-color: #fff5f5; }
    .bg-soft-info { background-color: #f0f9ff; }
    .avatar-box {
        width: 42px;
        height: 42px;
        color: white;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff !important;
        border-color: #4e73df !important;
    }
    .italic { font-style: italic; }
</style>

<script>
    function toggleSiswa() {
        var role = document.getElementById('role_select').value;
        var fields = document.getElementById('siswa_fields');
        fields.style.display = (role === 'admin') ? 'none' : 'block';
    }

    function toggleEditSiswa() {
        var role = document.getElementById('edit_role').value;
        var fields = document.getElementById('edit_siswa_fields');
        fields.style.display = (role === 'admin') ? 'none' : 'block';
    }

    function editUser(user) {
        document.getElementById('formEdit').action = "/admin/anggota/update/" + user.id;
        document.getElementById('edit_nama').value = user.nama;
        document.getElementById('edit_username').value = user.username;
        document.getElementById('edit_role').value = user.role;
        document.getElementById('edit_nis').value = user.nis || '';
        document.getElementById('edit_kelas').value = user.kelas || '10';
        document.getElementById('edit_jurusan').value = user.jurusan || 'RPL';
        toggleEditSiswa();
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleSiswa();
    });
</script>
@endsection