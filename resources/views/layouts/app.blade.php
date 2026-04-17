<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libify | Digital Library</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --bg-body: #f8faff;
            --card-shadow: 0 10px 30px rgba(67, 97, 238, 0.05);
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-body); 
            color: #1a202c;
            overflow-x: hidden;
        }

        /* Modern Glassmorphism Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.5);
            padding: 12px 0;
            transition: all 0.3s ease;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -1px;
        }

        /* Nav Links Styling */
        .nav-link {
            color: #718096 !important;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 10px 20px !important;
            margin: 0 4px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: var(--primary-color);
            border-radius: 10px;
        }

        /* User Profile & Hover Effect */
        .user-profile-link {
            text-decoration: none !important;
        }

        .user-profile {
            display: flex;
            align-items: center;
            padding: 6px 16px 6px 6px;
            background: #ffffff;
            border-radius: 100px;
            border: 1px solid #edf2f7;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            transition: all 0.3s ease;
        }

        .user-profile:hover {
            border-color: var(--primary-color);
            background: #f0f5ff;
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
        }

        .role-badge {
            font-size: 0.65rem;
            text-transform: uppercase;
            padding: 2px 8px;
            border-radius: 100px;
            background: #f0f5ff;
            color: var(--primary-color);
            font-weight: 800;
        }

        .btn-logout {
            background: #fff;
            color: #e53e3e;
            border: 1.5px solid #fed7d7;
            padding: 8px 18px;
            font-weight: 700;
            border-radius: 100px;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #fff5f5;
            border-color: #e53e3e;
        }

        main { animation: fadeIn 0.5s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================================
           BAGIAN PERUBAHAN TABEL (DITAMBAHKAN)
           ============================================================ */
        
        /* Wadah Tabel yang membatasi scroll */
        .table-responsive-scroll {
            max-height: 400px; /* Data akan terpotong setelah baris ke-5/6 */
            overflow-y: auto;
            border-radius: 0 0 12px 12px;
        }

        /* Membuat header tabel tetap diam di atas (Sticky) */
        .table thead th {
            position: sticky;
            top: 0;
            z-index: 10;
            background-color: #ffffff !important;
            border-bottom: 2px solid #edf2f7 !important;
            box-shadow: inset 0 -1px 0 #edf2f7;
        }

        /* Mempercantik Scrollbar */
        .table-responsive-scroll::-webkit-scrollbar {
            width: 6px;
        }
        .table-responsive-scroll::-webkit-scrollbar-track {
            background: #f8faff;
        }
        .table-responsive-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }
        .table-responsive-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--primary-color);
        }

        /* Merapikan sel tabel */
        .table td {
            padding: 1rem 0.75rem;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fas fa-book-sparkles me-2"></i>Libify
            </a>
            
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars-staggered"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-5">
                    @if(auth()->check() && auth()->user()->role == 'admin')
                        <li class="nav-item"><a class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}" href="/admin/dashboard">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::is('admin/buku*') ? 'active' : '' }}" href="/admin/buku">Katalog</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::is('admin/anggota*') ? 'active' : '' }}" href="/admin/anggota">Anggota</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::is('transaksi') ? 'active' : '' }}" href="/transaksi">Semua Transaksi</a></li>
                    @elseif(auth()->check())
                        <li class="nav-item"><a class="nav-link {{ Request::is('siswa/dashboard') ? 'active' : '' }}" href="/siswa/dashboard">Home</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::is('transaksi/pinjam') ? 'active' : '' }}" href="{{ route('transaksi.pinjam') }}">Peminjaman</a></li>
                        <li class="nav-item"><a class="nav-link {{ Request::is('transaksi/riwayat') ? 'active' : '' }}" href="{{ route('transaksi.riwayat') }}">Riwayat</a></li>
                    @endif
                </ul>

                @if(auth()->check())
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('profil') }}" class="user-profile-link">
                        <div class="user-profile">
                            <div class="user-avatar me-2">{{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}</div>
                            <div class="me-2 d-none d-sm-block">
                                <div class="fw-bold small text-dark" style="font-size: 0.85rem;">{{ auth()->user()->nama }}</div>
                                <span class="role-badge">{{ auth()->user()->role }}</span>
                            </div>
                        </div>
                    </a>
                    
                    <form action="/logout" method="POST" class="m-0">
                        @csrf 
                        <button type="submit" class="btn btn-logout btn-sm">
                            <i class="fas fa-power-off me-1"></i> Keluar
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </nav>

    <main class="py-5">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 2500
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4361ee'
            });
        @endif
    </script>
</body>
</html>