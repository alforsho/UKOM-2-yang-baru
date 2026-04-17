@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0 mx-auto rounded-4 overflow-hidden" style="max-width: 500px;">
        {{-- Header Warna Dinamis --}}
        <div class="card-header {{ auth()->user()->role == 'admin' ? 'bg-primary' : 'bg-info' }} py-3 border-0">
            <h5 class="card-title mb-0 text-white text-center fw-bold">
                <i class="fas fa-edit me-2"></i>EDIT TRANSAKSI
            </h5>
        </div>
        
        <div class="card-body p-4 bg-white">
            <form action="{{ route('transaksi.update', $transaksi->id_transaksi) }}" method="POST">
                @csrf
                
                {{-- Data Tanggal (Hidden agar tetap terjaga) --}}
                <input type="hidden" name="tanggal_peminjaman" value="{{ $transaksi->tanggal_peminjaman }}">
                <input type="hidden" name="tanggal_pengembalian" value="{{ $transaksi->tanggal_pengembalian }}">

                {{-- Bagian Nama Siswa --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Nama Siswa</label>
                    @if(auth()->user()->role == 'admin')
                        <select name="id" class="form-select bg-light border-0 py-2 shadow-none">
                            @foreach(DB::table('users')->where('role', 'siswa')->get() as $u)
                                <option value="{{ $u->id }}" {{ $u->id == $transaksi->id ? 'selected' : '' }}>
                                    {{ $u->nama }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="id" value="{{ auth()->id() }}">
                        <input type="text" class="form-control bg-light border-0 py-2" value="{{ auth()->user()->nama }}" readonly>
                    @endif
                </div>

                {{-- Bagian Judul Buku --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Judul Buku</label>
                    @if(auth()->user()->role == 'admin')
                        <select name="id_buku" class="form-select bg-light border-0 py-2 shadow-none">
                            @foreach($bukus as $b)
                                <option value="{{ $b->id_buku }}" {{ $b->id_buku == $transaksi->id_buku ? 'selected' : '' }}>
                                    {{ $b->nama_buku }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="id_buku" value="{{ $transaksi->id_buku }}">
                        <input type="text" class="form-control bg-light border-0 py-2" value="{{ $transaksi->nama_buku }}" readonly>
                    @endif
                </div>

                {{-- Bagian Status (Kunci Utama Approval) --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted text-uppercase">Status Transaksi</label>
                    @if(auth()->user()->role == 'admin')
                        <select name="status" class="form-select border-primary-subtle py-2 shadow-none fw-bold text-primary">
                            <option value="Pending" {{ $transaksi->status == 'Pending' ? 'selected' : '' }}>⌛ PENDING (Menunggu)</option>
                            <option value="Dipinjam" {{ $transaksi->status == 'Dipinjam' ? 'selected' : '' }}>📖 DIPINJAM (Setujui)</option>
                            <option value="Dikembalikan" {{ $transaksi->status == 'Dikembalikan' ? 'selected' : '' }}>✅ DIKEMBALIKAN</option>
                        </select>
                        <div class="mt-2 small text-muted">
                            <i class="fas fa-info-circle me-1"></i> Ubah ke <strong>Dipinjam</strong> untuk menyetujui pengajuan siswa.
                        </div>
                    @else
                        <input type="hidden" name="status" value="{{ $transaksi->status }}">
                        <div class="form-control bg-light border-0 py-2 fw-bold text-secondary">
                            {{ $transaksi->status }}
                        </div>
                    @endif
                </div>

                {{-- Tombol Aksi --}}
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary fw-bold py-2 rounded-pill shadow-sm">
                        <i class="fas fa-save me-1"></i> SIMPAN PERUBAHAN
                    </button>
                    <a href="{{ route('transaksi.index') }}" class="btn btn-light fw-bold py-2 rounded-pill text-muted">
                        BATAL
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-select:focus {
        border-color: #4361ee;
        background-color: #f8f9ff;
    }
    .card {
        animation: fadeInUp 0.4s ease-out;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection