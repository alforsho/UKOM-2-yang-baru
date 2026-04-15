<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Anggota;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // --- AUTHENTICATION ---

    // Halaman Login
    public function showLogin() { 
        return view('auth.login'); 
    }

    // Halaman Daftar
    public function showRegister() { 
        return view('auth.register'); 
    }

    // Proses Daftar (Khusus Siswa)
    public function register(Request $request) {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:5',
            'nis' => 'required|unique:anggota',
            'kelas' => 'required|in:10,11,12',
            'jurusan' => 'required|in:RPL,TKJ,MM,DKV,SIJA,AKL,OTKP',
            'no_telp' => 'required',
        ]);

        $user = User::create([
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        Anggota::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'nama_anggota' => $request->nama,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'no_telp' => $request->no_telp,
        ]);

        return redirect('/login')->with('success', 'Daftar berhasil! Silahkan login.');
    }

    // Proses Login
    public function login(Request $request) {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (auth()->user()->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            return redirect()->intended('/siswa/dashboard');
        }

        return back()->with('loginError', 'Username atau Password salah!');
    }

    // Proses Logout
    public function logout() {
        Auth::logout();
        return redirect('/login');
    }

    // --- FITUR PROFIL ---

    // Tampilkan Halaman Profil
    public function showProfil()
    {
        $id = Auth::id();
        
        // Mengambil data User gabungan dengan tabel Anggota (jika ada)
        $user = User::select('users.*', 'anggota.nis', 'anggota.kelas', 'anggota.jurusan', 'anggota.no_telp')
            ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
            ->where('users.id', $id)
            ->first();

        return view('auth.profil', compact('user'));
    }

    // Proses Update Profil (Nama & Password)
    public function updateProfil(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nama' => 'required|string|max:255',
            'password' => 'nullable|min:5|confirmed', // butuh input password_confirmation di view
        ]);

        DB::transaction(function () use ($request, $user) {
            
            // 1. Update Tabel Users
            $updateData = [
                'nama' => $request->nama,
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($request->password);
            }

            User::where('id', $user->id)->update($updateData);

            // 2. Update Tabel Anggota (Jika user adalah siswa)
            if ($user->role == 'siswa') {
                Anggota::where('user_id', $user->id)->update([
                    'nama_anggota' => $request->nama,
                    'kelas' => $request->kelas,
                    'jurusan' => $request->jurusan,
                    'no_telp' => $request->no_telp,
                ]);
            }
        });

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}