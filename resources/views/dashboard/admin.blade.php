@extends('layouts.app')

@section('content')
<div class="container-fluid py-4 px-4">
    {{-- 1. Header Section --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
        <div>
            <h3 class="fw-bold text-primary mb-0">Dashboard Admin</h3>
            <p class="text-muted mb-0">Halo, <strong>{{ auth()->user()->nama }}</strong>. Berikut ringkasan perpustakaan hari ini.</p>
        </div>
        <div>
            <span class="badge bg-white text-primary border px-3 py-2 rounded-pill shadow-sm">
                <i class="fas fa-calendar-alt me-2"></i>{{ date('d M Y') }}
            </span>
        </div>
    </div>

    <div class="row g-4">
        {{-- 2. Bar Chart Section (Kiri) --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-dark mb-0">Statistik Perpustakaan</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light rounded-circle" type="button"><i class="fas fa-ellipsis-v"></i></button>
                    </div>
                </div>
                <div class="chart-container" style="position: relative; height: 350px; width: 100%;">
                    <canvas id="libraryBarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- 3. Stats Cards Section (Kanan) --}}
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                {{-- Card Buku --}}
                <div class="card border-0 shadow-sm card-hover p-3 position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold small">KOLEKSI BUKU</h6>
                            <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Buku::count() }}</h2>
                        </div>
                        <div class="icon-circle bg-primary text-white shadow-sm">
                            <i class="fas fa-book fa-lg"></i>
                        </div>
                    </div>
                    <a href="/admin/buku" class="text-primary small text-decoration-none fw-bold mt-3 stretched-link">
                        Manage <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </div>

                {{-- Card Anggota --}}
                <div class="card border-0 shadow-sm card-hover p-3 position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold small">TOTAL ANGGOTA</h6>
                            <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Anggota::count() }}</h2>
                        </div>
                        <div class="icon-circle bg-success text-white shadow-sm">
                            <i class="fas fa-users fa-lg"></i>
                        </div>
                    </div>
                    <a href="/admin/anggota" class="text-success small text-decoration-none fw-bold mt-3 stretched-link">
                        Detail <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </div>

                {{-- Card Transaksi --}}
                <div class="card border-0 shadow-sm card-hover p-3 position-relative">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="text-muted mb-1 fw-bold small">TOTAL TRANSAKSI</h6>
                            <h2 class="fw-bold mb-0 text-dark">{{ \App\Models\Transaksi::count() }}</h2>
                        </div>
                        <div class="icon-circle bg-warning text-white shadow-sm">
                            <i class="fas fa-exchange-alt fa-lg"></i>
                        </div>
                    </div>
                    <a href="/transaksi" class="text-warning small text-decoration-none fw-bold mt-3 stretched-link">
                        Laporan <i class="fas fa-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    body { 
        background-color: #f8f9fa; 
        min-height: 100vh;
    }
    .card-hover { 
        transition: all 0.3s cubic-bezier(.25,.8,.25,1); 
        border-radius: 20px; 
    }
    .card-hover:hover { 
        transform: translateY(-5px); 
        box-shadow: 0 12px 20px rgba(0,0,0,0.08) !important; 
    }
    .icon-circle { 
        width: 55px; 
        height: 55px; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 15px; 
    }
    .chart-container {
        min-height: 300px;
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('libraryBarChart').getContext('2d');
        
        // Gradient effect
        const gradientBlue = ctx.createLinearGradient(0, 0, 0, 400);
        gradientBlue.addColorStop(0, 'rgba(13, 110, 253, 1)');
        gradientBlue.addColorStop(1, 'rgba(13, 110, 253, 0.3)');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Buku', 'Anggota', 'Transaksi'],
                datasets: [{
                    label: 'Jumlah',
                    data: [
                        {{ \App\Models\Buku::count() }}, 
                        {{ \App\Models\Anggota::count() }}, 
                        {{ \App\Models\Transaksi::count() }}
                    ],
                    backgroundColor: [
                        gradientBlue, 
                        'rgba(25, 135, 84, 0.8)', 
                        'rgba(255, 193, 7, 0.8)'
                    ],
                    borderRadius: 10,
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
                        grid: { color: '#f0f0f0', drawBorder: false }
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