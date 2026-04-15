@extends('layouts.app')

@section('content')
<div class="container py-4">
    {{-- 1. Header Section --}}
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold text-primary">Dashboard Admin</h2>
            <p class="text-muted">Selamat Datang kembali, <strong>{{ auth()->user()->nama }}</strong>. Berikut ringkasan data perpustakaan hari ini.</p>
        </div>
    </div>

    {{-- 2. Bar Chart Section (Sekarang di Atas) --}}
    <div class="row mb-5">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Statistik Data Perpustakaan</h5>
                        <small class="text-muted">Perbandingan jumlah Buku, Anggota, dan Transaksi</small>
                    </div>
                    <span class="badge bg-light text-dark border">Data Real-time</span>
                </div>
                <div style="position: relative; height:300px;">
                    <canvas id="libraryBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Stats Cards Section (Sekarang di Bawah Grafik) --}}
    <div class="row g-4">
        {{-- Card Buku --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-hover h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase">Koleksi Buku</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Buku::count() }}</h2>
                    </div>
                    <div class="icon-circle bg-primary text-white shadow-sm">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
                <hr class="my-3 opacity-25">
                <a href="/admin/buku" class="text-primary small text-decoration-none fw-bold">
                    Kelola Data Buku <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Card Anggota --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-hover h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase">Total Anggota</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Anggota::count() }}</h2>
                    </div>
                    <div class="icon-circle bg-success text-white shadow-sm">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
                <hr class="my-3 opacity-25">
                <a href="/admin/anggota" class="text-success small text-decoration-none fw-bold">
                    Lihat Semua Siswa <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>

        {{-- Card Transaksi --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm card-hover h-100 p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1 fw-bold small text-uppercase">Transaksi Pinjam</h6>
                        <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Transaksi::count() }}</h2>
                    </div>
                    <div class="icon-circle bg-warning text-white shadow-sm">
                        <i class="fas fa-exchange-alt fa-2x"></i>
                    </div>
                </div>
                <hr class="my-3 opacity-25">
                <a href="/transaksi" class="text-warning small text-decoration-none fw-bold">
                    Cek Laporan Transaksi <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

{{-- CSS Custom --}}
<style>
    .card-hover {
        transition: all 0.3s ease;
        border-radius: 15px;
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .icon-circle {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
    }
</style>

{{-- JS Scripts --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('libraryBarChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Buku', 'Anggota', 'Transaksi'],
                datasets: [{
                    label: 'Jumlah Total',
                    data: [
                        {{ \App\Models\Buku::count() }}, 
                        {{ \App\Models\Anggota::count() }}, 
                        {{ \App\Models\Transaksi::count() }}
                    ],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.85)', // Blue
                        'rgba(25, 135, 84, 0.85)',  // Green
                        'rgba(255, 193, 7, 0.85)'   // Yellow
                    ],
                    borderColor: [
                        '#0d6efd',
                        '#198754',
                        '#ffc107'
                    ],
                    borderWidth: 1,
                    borderRadius: 10,
                    borderSkipped: false,
                    barPercentage: 0.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f0f0f0', drawBorder: false },
                        ticks: { precision: 0 }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection