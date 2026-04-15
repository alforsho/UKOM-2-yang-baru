@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="user-avatar me-3" style="width: 60px; height: 60px; font-size: 1.5rem; background: #0d6efd; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        {{ strtoupper(substr($user->nama, 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark">{{ $user->nama }}</h4>
                        <p class="text-muted mb-0 small">Kelola informasi profil dan keamanan akun Anda</p>
                    </div>
                </div>

                <form action="{{ route('profil.update') }}" method="POST">
                    @csrf
                    
                    <h6 class="fw-bold text-primary mb-3">Informasi Dasar</h6>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Username</label>
                        <input type="text" class="form-control bg-light border-0 py-2" value="{{ $user->username }}" readonly disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control py-2 @error('nama') is-invalid @enderror" value="{{ old('nama', $user->nama) }}" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    @if(auth()->user()->role == 'siswa')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">NIS</label>
                            <input type="text" class="form-control bg-light border-0 py-2" value="{{ $user->nis }}" readonly disabled>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control py-2" value="{{ old('no_telp', $user->no_telp) }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Kelas</label>
                            <select name="kelas" class="form-select py-2">
                                <option value="10" {{ $user->kelas == '10' ? 'selected' : '' }}>10</option>
                                <option value="11" {{ $user->kelas == '11' ? 'selected' : '' }}>11</option>
                                <option value="12" {{ $user->kelas == '12' ? 'selected' : '' }}>12</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-secondary">Jurusan</label>
                            <select name="jurusan" class="form-select py-2">
                                @foreach(['RPL', 'TKJ', 'MM', 'DKV', 'SIJA', 'AKL', 'OTKP'] as $j)
                                    <option value="{{ $j }}" {{ $user->jurusan == $j ? 'selected' : '' }}>{{ $j }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif

                    <hr class="my-4 opacity-25">
                    
                    <h6 class="fw-bold text-primary mb-3">Keamanan Akun</h6>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-secondary">Password Baru (Isi jika ingin diganti)</label>
                        <input type="password" name="password" class="form-control py-2 @error('password') is-invalid @enderror" placeholder="••••••••">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-secondary">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control py-2" placeholder="••••••••">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-bold py-2 rounded-3 shadow-sm">
                            <i class="fas fa-save me-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection