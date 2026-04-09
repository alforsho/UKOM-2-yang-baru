@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
                <a href="/transaksi" class="btn btn-white shadow-sm rounded-circle me-3">
                    <i class="fas fa-arrow-left text-primary"></i>
                </a>
                <div>
                    <h4 class="fw-bold mb-0">Tambah Peminjaman</h4>
                    <p class="text-muted small mb-0">Input data peminjaman buku baru</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="/transaksi/store" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">PEMINJAM (ANGGOTA)</label>
                            <select name="user_id" class="form-select border-0 bg-light py-2" required>
                                <option value="" selected disabled>-- Pilih Siswa --</option>
                                @foreach($anggota as $a)
                                    <option value="{{ $a->user_id }}">{{ $a->nama_anggota }} ({{ $a->nis }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">BUKU YANG DIPINJAM</label>
                            <select name="id_buku" class="form-select border-0 bg-light py-2" required>
                                <option value="" selected disabled>-- Pilih Judul Buku --</option>
                                @foreach($buku as $b)
                                    <option value="{{ $b->id }}">{{ $b->judul }} (Stok: {{ $b->stok }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-secondary">TANGGAL PINJAM</label>
                            <input type="date" name="tgl_pinjam" class="form-select border-0 bg-light py-2" value="{{ date('Y-m-d') }}" required>
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
        border: 1px solid #0d6efd !important;
    }
</style>
@endsection