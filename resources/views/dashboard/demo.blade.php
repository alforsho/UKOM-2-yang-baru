<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Libify | Katalog Buku Minimalis</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0d6efd; /* Warna sesuai tombol login kamu */
            --soft-bg: #f5f7fa;
            --text-dark: #1a202c;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--soft-bg);
            color: var(--text-dark);
            margin: 0;
            padding-bottom: 50px;
        }

        /* Navbar Style ala Login */
        .navbar { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        .navbar-brand { font-weight: 800; letter-spacing: 1px; color: var(--primary-blue) !important; font-size: 1.5rem; }
        .btn-nav { font-weight: 700; border-radius: 12px; padding: 8px 24px; transition: all 0.3s; }
        .btn-login-nav { border: 2px solid var(--primary-blue); color: var(--primary-blue); }
        .btn-register-nav { background: var(--primary-blue); color: white; margin-left: 10px; }
        .btn-nav:hover { transform: translateY(-2px); opacity: 0.9; }

        /* Search Section */
        .search-section { padding: 40px 0; background: white; margin-bottom: 40px; }
        .search-box { 
            background: #f8fafc; border-radius: 20px; border: 1px solid #e2e8f0; padding: 5px;
        }
        .search-box input { border: none; background: transparent; padding-left: 15px; }
        .search-box input:focus { box-shadow: none; background: transparent; }
        .btn-search { border-radius: 15px; padding: 10px 25px; background: var(--primary-blue); }

        /* ====== BOOK CARD STYLE BARU (Kotak Minimalis Warna) ====== */
        .book-card {
            background: white; border-radius: 20px; border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%; display: flex; flex-direction: column; overflow: hidden;
        }
        .book-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }

        /* Bagian Kotak Warna Atas */
        .book-cover-color {
            height: 200px; display: flex; align-items: center; justify-content: center;
            position: relative; border-radius: 20px 20px 0 0;
        }
        .cover-initial { font-weight: 800; font-size: 4rem; color: rgba(255,255,255,0.8); text-transform: uppercase; }
        
        /* Badge Stok di Pojok Kiri Atas */
        .badge-stok {
            position: absolute; top: 15px; left: 15px; 
            background: rgba(255,255,255,0.9); color: var(--primary-blue);
            padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 0.75rem;
        }

        /* Bagian Detail Bawah */
        .book-details { padding: 20px; flex-grow: 1; display: flex; flex-direction: column; }
        .book-title {
            font-weight: 700; font-size: 1.1rem; margin-bottom: 5px; color: var(--text-dark);
            height: 2.7rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
        }
        .book-info { font-size: 0.85rem; color: #718096; margin-bottom: 20px; }
        
        /* Tombol Pinjam Bulat minimalis */
        .btn-pinjam {
            border-radius: 12px; padding: 10px; font-weight: 700;
            background: #f8fafc; border: 1px solid #e2e8f0; color: #4a5568;
            transition: all 0.3s;
        }
        .btn-pinjam:hover { background: #edf2f7; color: var(--text-dark); border-color: #cbd5e0; }
        .book-card:hover .btn-pinjam { background: var(--primary-blue); color: white; border-color: var(--primary-blue); }

    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light sticky-top py-3">
        <div class="container">
            <a class="navbar-brand" href="/">Libify</a>
            <div class="ms-auto d-flex">
                <a href="/login" class="btn btn-nav btn-login-nav">Masuk</a>
                <a href="/register" class="btn btn-nav btn-register-nav shadow-sm">Daftar</a>
            </div>
        </div>
    </nav>

    <section class="search-section">
        <div class="container text-center">
            <h2 class="fw-bold mb-3">Selamat Datang di Katalog</h2>
            <p class="text-muted mb-4">Cari dan pinjam koleksi buku digital kami.</p>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="/" method="GET" class="search-box d-flex">
                        <input type="text" name="search" class="form-control border-0" placeholder="Cari judul..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary btn-search shadow-sm"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <main class="container">
        <div class="row g-4">
            {{-- Loop Buku dari DB --}}
            @php
                // Array warna random ala kartu di gambar kamu
                $colors = ['#5bc0de', '#007bff', '#28a745', '#17a2b8', '#dc3545', '#ffc107', '#6f42c1'];
            @endphp

            @forelse($books as $buku)
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="book-card">
                    {{-- Bagian Atas: Kotak Warna Minimalis --}}
                    @php
                        // Pilih warna random dari array berdasarkan ID agar konsisten
                        $randColor = $colors[$buku->id % count($colors)];
                        // Ambil 2 huruf pertama judul untuk initial
                        $initial = strtoupper(substr($buku->nama_buku, 0, 2));
                    @endphp
                    <div class="book-cover-color" style="background-color: {{ $randColor }};">
                        <span class="cover-initial">{{ $initial }}</span>
                        {{-- Stok di Pojok Kiri --}}
                        <span class="badge-stok shadow-sm">Stok: {{ $buku->stok }}</span>
                    </div>
                    
                    {{-- Bagian Bawah: Detail --}}
                    <div class="book-details">
                        <h5 class="book-title" title="{{ $buku->nama_buku }}">{{ $buku->nama_buku }}</h5>
                        <div class="book-info">
                            <i class="fas fa-feather-alt me-1"></i> {{ $buku->penerbit }}
                        </div>

                        <div class="mt-auto">
                            @if($buku->stok > 0)
                                {{-- Tombol Pinjam yang memicu SweetAlert suruh login --}}
                                <button onclick="mintaLogin('{{ $buku->nama_buku }}')" class="btn btn-pinjam w-100">
                                    <i class="fas fa-hand-holding-medical me-2"></i>Pinjam
                                </button>
                            @else
                                <button class="btn btn-light w-100 disabled text-muted" style="border-radius: 12px;">Habis</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <p class="text-muted">Buku belum tersedia.</p>
            </div>
            @endforelse
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function mintaLogin(judul) {
            Swal.fire({
                title: 'Tertarik meminjam?',
                text: "Untuk meminjam '" + judul + "', kamu harus login sebagai Siswa dulu ya.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#0d6efd', // Biru sesuai tombol login kamu
                cancelButtonColor: '#e2e8f0',
                confirmButtonText: 'Masuk Sekarang',
                cancelButtonText: '<span style="color: #4a5568">Nanti Saja</span>',
                borderRadius: '20px', // Rounded minimalis ala kartu
                customClass: {
                    cancelButton: 'border'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "/login";
                }
            })
        }
    </script>
</body>
</html>