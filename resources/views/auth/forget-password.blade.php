<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Sistem Perpustakaan Digital</title>
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
            background: #0d6efd; /* Warna Biru sesuai Login */
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Input Group Style sesuai Gambar Login */
        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px !important;
            color: #a0aec0;
            padding-left: 1.25rem;
        }

        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0 12px 12px 0 !important;
            border: 1px solid #e2e8f0;
            border-left: none;
            background-color: #f8fafc;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #e2e8f0;
            box-shadow: none;
            outline: none;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #0d6efd;
            background-color: #fff;
        }

        /* Button Style sesuai Gambar Login */
        .btn-reset {
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 700;
            background: #0d6efd;
            border: none;
            color: white;
            transition: all 0.3s ease;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-reset:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
            color: white;
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
            <i class="fas fa-key"></i>
        </div>
        
        <h2>Lupa Password?</h2>
        <p class="subtitle">Atur ulang password akun Anda</p>

        @if(session('error')) 
            <div class="alert alert-danger border-0 small fw-bold mb-4" style="border-radius: 12px;">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            </div> 
        @endif
        
        <form action="{{ route('password.update.langsung') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username Anda" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password Baru</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-check-double"></i></span>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn btn-reset w-100">
                Update Password <i class="fas fa-arrow-right"></i>
            </button>
            
            <p class="footer-text">
                Sudah ingat? <a href="/login">Kembali Login</a>
            </p>
        </form>
    </div>

</body>
</html>