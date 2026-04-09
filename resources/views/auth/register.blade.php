<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota | Sistem Perpustakaan Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f5f7fa; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 40px 0;
        }

        .register-card {
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 500px;
            border: 1px solid #e2e8f0;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: #0d6efd;
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        h2 { 
            font-weight: 700; 
            color: #1a202c; 
            margin-bottom: 0.5rem;
        }

        p.subtitle {
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #4a5568;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .form-control, .form-select {
            padding: 0.65rem 1rem;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            background-color: #fff;
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        }

        .btn-register {
            padding: 0.75rem;
            border-radius: 10px;
            font-weight: 700;
            background: #0d6efd;
            border: none;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-register:hover {
            background: #0b5ed7;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.2);
        }

        .error-alert {
            background-color: #fff5f5;
            border-left: 4px solid #f56565;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
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

        .input-group-text {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #a0aec0;
        }

        .has-icon .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="brand-logo">
            <i class="fas fa-user-plus"></i>
        </div>
        
        <h2>Daftar Anggota</h2>
        <p class="subtitle">Lengkapi data untuk akses perpustakaan digital</p>

        {{-- Menampilkan Error Validasi --}}
        @if ($errors->any())
            <div class="error-alert">
                <ul class="mb-0 ps-3 text-danger small fw-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/register" method="POST">
            @csrf
            
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Nama sesuai absen" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" value="{{ old('username') }}" placeholder="Untuk login" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 5 karakter" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" value="{{ old('nis') }}" placeholder="Nomor Induk" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-select" required>
                        <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>Pilih</option>
                        <option value="10" {{ old('kelas') == '10' ? 'selected' : '' }}>10</option>
                        <option value="11" {{ old('kelas') == '11' ? 'selected' : '' }}>11</option>
                        <option value="12" {{ old('kelas') == '12' ? 'selected' : '' }}>12</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Jurusan</label>
                <select name="jurusan" class="form-select" required>
                    <option value="" disabled {{ old('jurusan') ? '' : 'selected' }}>Pilih Jurusan Anda</option>
                    <option value="RPL" {{ old('jurusan') == 'RPL' ? 'selected' : '' }}>Rekayasa Perangkat Lunak (RPL)</option>
                    <option value="TKJ" {{ old('jurusan') == 'TKJ' ? 'selected' : '' }}>Teknik Komputer Jaringan (TKJ)</option>
                    <option value="MM" {{ old('jurusan') == 'MM' ? 'selected' : '' }}>Multimedia (MM)</option>
                    <option value="DKV" {{ old('jurusan') == 'DKV' ? 'selected' : '' }}>Desain Komunikasi Visual (DKV)</option>
                    <option value="SIJA" {{ old('jurusan') == 'SIJA' ? 'selected' : '' }}>SIJA</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">No. Telepon</label>
                <div class="input-group has-icon">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="text" name="no_telp" class="form-control" value="{{ old('no_telp') }}" placeholder="0812xxxx" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-register w-100">
                Buat Akun Sekarang <i class="fas fa-paper-plane ms-2"></i>
            </button>
        </form>

        <p class="footer-text">
            Sudah punya akun? <a href="/login">Masuk di sini</a>
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>