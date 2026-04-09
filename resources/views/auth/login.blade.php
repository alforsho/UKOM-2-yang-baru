<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f5f7fa; 
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

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
            background: #0d6efd;
            color: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 16px rgba(13, 110, 253, 0.2);
        }

        h2 { 
            font-weight: 700; 
            color: #1a202c; 
            text-align: center;
            margin-bottom: 0.5rem;
        }

        p.subtitle {
            text-align: center;
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #4a5568;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .btn-login {
            padding: 0.75rem;
            border-radius: 12px;
            font-weight: 700;
            background: #0d6efd;
            border: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-login:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .alert {
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #718096;
        }

        .footer-text a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand-logo">
            <i class="fas fa-book-reader"></i>
        </div>
        
        <h2>Selamat Datang</h2>
        <p class="subtitle">Silakan masuk ke akun Anda</p>

        {{-- Pesan Error --}}
        @if(session('loginError')) 
            <div class="alert alert-danger d-flex align-items-center mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('loginError') }}
            </div> 
        @endif

        {{-- Pesan Sukses --}}
        @if(session('success')) 
            <div class="alert alert-success d-flex align-items-center mb-4">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
            </div> 
        @endif
        
        <form action="/login" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">USERNAME</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light"><i class="fas fa-user text-muted"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">PASSWORD</label>
                <div class="input-group">
                    <span class="input-group-text border-0 bg-light"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-login w-100">
                Masuk Sekarang <i class="fas fa-arrow-right ms-2"></i>
            </button>
        </form>

        <p class="footer-text">
            Belum punya akun? <a href="/register">Daftar Siswa</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>