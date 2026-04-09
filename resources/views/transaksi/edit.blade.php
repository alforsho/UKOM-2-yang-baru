@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto rounded-4 overflow-hidden" style="max-width: 500px;">
        <div class="card-header {{ auth()->user()->role == 'admin' ? 'bg-primary' : 'bg-info' }} py-3 border-0">
            <h5 class="card-title mb-0 text-white text-center fw-bold">
                <i class="fas fa-edit me-2"></i>EDIT TRANSAKSI
            </h5>
        </div>
        <div class="card-body p-4 bg-white">
            <form action="/transaksi/update/{{ $transaksi->id_transaksi }}" method="POST">
                @csrf
                
                {{-- Nama Siswa --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Nama Siswa</label>
                    @if(auth()->user()->role == 'admin')
                        <select name="user_id" class="form-select bg-light border-0 py-2">
                            @foreach(DB::table('users')->where('role', 'siswa')->get() as $u)
                                <option value="{{ $u->id }}" {{ $u->id == $transaksi->id ? 'selected' : '' }}>
                                    {{ $u->nama }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" class="form-control bg-light border-0 py-2" value="{{ auth()->user()->nama }}" readonly>
                    @endif
                </div>

                {{-- Judul Buku --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                    <select name="id_buku" class="form-select bg-light border-0 py-2">
                        @foreach($bukus as $b)
                            <option value="{{ $b->id_buku }}" {{ $b->id_buku == $transaksi->id_buku ? 'selected' : '' }}>
                                {{ $b->nama_buku }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Status Transaksi</label>
                    @if(auth()->user()->role == 'admin')
                        <select name="status" class="form-select bg-light border-0 py-2">
                            {{-- Menyesuaikan dengan status yang kita pakai di controller --}}
                            <option value="Dipinjam" {{ $transaksi->status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ $transaksi->status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        </select>
                    @else
                        <div class="form-control bg-light border-0 py-2 text-muted italic">
                            {{ $transaksi->status }}
                        </div>
                    @endif
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm">
                        SIMPAN PERUBAHAN
                    </button>
                    <a href="/transaksi" class="btn btn-light fw-bold py-2 rounded-pill text-muted">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection