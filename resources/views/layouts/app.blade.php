<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f5f7fa; 
            color: #2d3748;
        }

        /* Modern Navbar */
        .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 0;
        }

        .navbar-brand {
            color: #0d6efd !important;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
        }

        .nav-link {
            color: #4a5568 !important;
            font-weight: 600;
            padding: 8px 16px !important;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .nav-link:hover, .nav-link.active {
            color: #0d6efd !important;
            background: rgba(13, 110, 253, 0.08);
        }

        /* User Profile Dropdown */
        .user-profile {
            display: flex;
            align-items: center;
            padding: 5px 15px;
            background: #f8fafc;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: #0d6efd;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }

        .role-badge {
            font-size: 0.7rem;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 4px;
            background: #e2e8f0;
            color: #4a5568;
            font-weight: 700;
        }

        /* Alert styling */
        .alert {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-book-reader me-2"></i>PERPUS-KU
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    @if(auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">
                                <i class="fas fa-chart-line me-1 small"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/buku*') ? 'active' : '' }}" href="/admin/buku">
                                <i class="fas fa-book me-1 small"></i> Data Buku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/anggota*') ? 'active' : '' }}" href="/admin/anggota">
                                <i class="fas fa-users me-1 small"></i> Anggota
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('siswa/dashboard') ? 'active' : '' }}" href="/siswa/dashboard">
                                <i class="fas fa-home me-1 small"></i> Dashboard
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('transaksi*') ? 'active' : '' }}" href="/transaksi">
                            <i class="fas fa-exchange-alt me-1 small"></i> Transaksi
                        </a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <div class="user-profile d-none d-md-flex">
                        <div class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                        </div>
                        <div class="me-3">
                            <div class="fw-bold small text-dark" style="line-height: 1.2;">{{ auth()->user()->nama }}</div>
                            <span class="role-badge">{{ auth()->user()->role }}</span>
                        </div>
                    </div>
                    
                    <form action="/logout" method="POST" class="m-0">
                        @csrf 
                        <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                            <i class="fas fa-sign-out-alt me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>