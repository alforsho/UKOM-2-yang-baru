@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            {{-- Header --}}
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('transaksi.index') }}" class="btn btn-white shadow-sm rounded-circle me-3">
                    <i class="fas fa-arrow-left text-primary"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Tambah Peminjaman</h4>
                    <p class="text-muted small mb-0">Input data peminjaman buku baru (Admin Mode)</p>
                </div>
            </div>

            {{-- Alert Error jika validasi gagal --}}
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-3">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="{{ route('transaksi.store') }}" method="POST">
                        @csrf
                        
                        {{-- Pilih Siswa --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">PEMINJAM (ANGGOTA)</label>
                            <select name="id" class="form-select border-0 bg-light py-2" required>
                                <option value="" selected disabled>-- Pilih Siswa --</option>
                                @foreach($list_anggota as $a)
                                    {{-- Gunakan ID dari tabel users sesuai logika Controller --}}
                                    <option value="{{ $a->id }}">{{ $a->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pilih Buku --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">BUKU YANG DIPINJAM</label>
                            <select name="id_buku" class="form-select border-0 bg-light py-2" required>
                                <option value="" selected disabled>-- Pilih Judul Buku --</option>
                                @foreach($bukus as $b)
                                    <option value="{{ $b->id_buku }}">{{ $b->nama_buku }} (Stok: {{ $b->stok }})</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Otomatis (Hanya Readonly karena Controller pakai now()) --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary">TANGGAL PINJAM</label>
                            <input type="text" class="form-control border-0 bg-light py-2" value="{{ date('d F Y') }}" readonly>
                            <small class="text-muted" style="font-size: 0.65rem;">*Batas pengembalian otomatis 7 hari dari hari ini.</small>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 rounded-3 fw-bold shadow-sm">
                                <i class="fas fa-save me-2"></i> Simpan Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn-white {
        background: #fff;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
    }
    .form-select:focus, .form-control:focus {
        background-color: #f0f5ff !important;
        box-shadow: none;
        border: 1px solid #4361ee !important;
    }
    .btn-primary {
        background-color: #4361ee;
        border: none;
    }
    .btn-primary:hover {
        background-color: #3f37c9;
    }
</style>
@endsection