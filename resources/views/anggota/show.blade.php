@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            {{-- Tombol Kembali --}}
            <div class="mb-3 no-print">
                <a href="/admin/anggota" class="text-decoration-none text-muted small">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Anggota
                </a>
            </div>

            <div class="card border-0 shadow-lg printable-card" style="border-radius: 15px; overflow: hidden; background: white;">
                <div class="bg-primary text-white text-center py-3">
                    <h5 class="mb-0 fw-bold">KARTU ANGGOTA PERPUSTAKAAN</h5>
                    <small>SMK YAJ</small>
                </div>

                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-4 text-center border-end">
                            <div class="mx-auto bg-light rounded-circle d-flex align-items-center justify-content-center mb-2" style="width: 80px; height: 80px; border: 2px solid #eee;">
                                <i class="fas fa-user text-secondary" style="font-size: 2rem;"></i>
                            </div>
                            <span class="badge bg-primary rounded-pill small">
                                ID #{{ str_pad($anggota->id, 5, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>

                        <div class="col-8">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted small fw-bold" style="width: 35%;">NAMA</td>
                                    <td class="small fw-bold">: {{ strtoupper($anggota->nama) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small fw-bold">NIS</td>
                                    <td class="small">: {{ $anggota->nis ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small fw-bold">KELAS</td>
                                    <td class="small">: {{ $anggota->kelas ?? '-' }} {{ $anggota->jurusan ?? '' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted small fw-bold">TELEPON</td>
                                    <td class="small text-truncate">: {{ $anggota->no_telp ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light text-center py-2">
                    <small class="text-muted italic">Berlaku selama menjadi siswa aktif</small>
                </div>
            </div>
            
            <div class="d-grid gap-2 mt-4 no-print">
                <button onclick="window.print()" class="btn btn-primary shadow-sm">
                    <i class="fas fa-print me-2"></i> Cetak Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f3f4f7; }
    
    /* CSS UNTUK TAMPILAN PRINT */
    @media print {
        /* Sembunyikan semua kecuali kartu */
        body * { visibility: hidden; }
        .printable-card, .printable-card * { visibility: visible; }
        
        /* Posisi kartu di kertas */
        .printable-card {
            visibility: visible;
            position: absolute;
            left: 50%;
            top: 50px;
            transform: translateX(-50%);
            width: 8.5cm; /* Ukuran standar kartu ID */
            border: 1px solid #000 !important;
            box-shadow: none !important;
            border-radius: 10px !important;
        }

        /* Pastikan warna tetap muncul */
        .bg-primary {
            background-color: #0d6efd !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .text-white { color: white !important; }
        .text-muted { color: #6c757d !important; }
        
        /* Hilangkan scroll bar */
        html, body {
            overflow: visible !important;
            height: auto !important;
            background: white !important;
        }
        
        .no-print { display: none !important; }
    }
</style>
@endsection