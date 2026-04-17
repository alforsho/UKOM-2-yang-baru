<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Libify Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-blue: #0d6efd;
            --soft-bg: #f5f7fa;
            --text-dark: #1a202c;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--soft-bg); 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
        }

        /* Tombol Kembali ke Demo (Pojok Kiri Atas) */
        .btn-back-home {
            position: absolute;
            top: 2rem;
            left: 2rem;
            text-decoration: none;
            color: #718096;
            font-weight: 700;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            padding: 0.6rem 1.2rem;
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .btn-back-home:hover {
            color: var(--primary-blue);
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-color: var(--primary-blue);
        }

        /* Card Login */
        .login-card {
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            border: 1px solid #e2e8f0;
        }

        .brand-logo {
            width: 60px;
            height: 60px;
            background: var(--primary-blue);
            color: white;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 16px rgba(13, 110, 253, 0.2);
        }

        h2 { 
            font-weight: 800; 
            color: var(--text-dark); 
            text-align: center;
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        p.subtitle {
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        /* Form Styling */
        .form-label {
            font-weight: 700;
            font-size: 0.75rem;
            color: #4a5568;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-right: none;
            color: #a0aec0;
        }

        .input-group .form-control {
            border-radius: 0 12px 12px 0;
        }

        /* Button Styling */
        .btn-login {
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 700;
            background: var(--primary-blue);
            border: none;
            color: white;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: #0b5ed7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
            color: white;
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #718096;
        }

        .footer-text a {
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 700;
        }

        .forget-link {
            font-size: 0.8rem;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 700;
        }

        /* Alert Styling */
        .alert {
            border: none;
            border-radius: 14px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        @media (max-width: 576px) {
            .btn-back-home {
                top: 1rem;
                left: 1rem;
                padding: 0.5rem 1rem;
            }
            .login-card {
                margin: 15px;
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>

    <a href="/" class="btn-back-home">
        <i class="fas fa-chevron-left me-2"></i> Katalog
    </a>

    <div class="login-card shadow-lg">
        <div class="brand-logo">
            <i class="fas fa-layer-group"></i>
        </div>
        
        <h2>Libify.</h2>
        <p class="subtitle">Masuk untuk meminjam buku</p>

        {{-- Notifikasi --}}
        @if(session('loginError')) 
            <div class="alert alert-danger d-flex align-items-center mb-4">
                <i class="fas fa-circle-exclamation me-2"></i>
                {{ session('loginError') }}
            </div> 
        @endif

        @if(session('success')) 
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fas fa-circle-check me-2"></i>
                {{ session('success') }}
            </div> 
        @endif
        
        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                    <input type="text" name="username" class="form-control shadow-none" placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <label class="form-label mb-0">Password</label>
                    <a href="{{ route('password.request') }}" class="forget-link">Lupa?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-shield-halved"></i></span>
                    <input type="password" name="password" class="form-control shadow-none" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 shadow-sm">
                Masuk Sekarang <i class="fas fa-arrow-right-long ms-2"></i>
            </button>
        </form>

        <p class="footer-text">
            Belum punya akun? <a href="/register">Buat Akun Siswa</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>