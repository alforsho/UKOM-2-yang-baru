<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index()
    {
        // Join manual tabel users dan anggota menggunakan Query Builder
        $users = DB::table('users')
            ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
            ->select('users.*', 'anggota.nis', 'anggota.kelas', 'anggota.jurusan', 'anggota.no_telp')
            ->orderBy('users.id', 'desc')
            ->get();

        return view('anggota.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            // Simpan ke tabel users
            $idUser = DB::table('users')->insertGetId([
                'nama' => $request->nama,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'created_at' => now(),
            ]);

            // Jika role siswa, isi tabel anggota
            if ($request->role == 'siswa') {
                DB::table('anggota')->insert([
                    'user_id' => $idUser,
                    'nis' => $request->nis,
                    'nama_anggota' => $request->nama,
                    'kelas' => $request->kelas,
                    'jurusan' => $request->jurusan,
                    'no_telp' => $request->no_telp,
                    'created_at' => now(),
                ]);
            }
        });

        return back()->with('success', 'Data berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'role' => 'required'
        ]);

        DB::transaction(function () use ($request, $id) {
            // 1. Update data User
            $userData = [
                'nama' => $request->nama,
                'username' => $request->username,
                'role' => $request->role,
                'updated_at' => now(),
            ];

            // Update password hanya jika kolom password diisi
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            DB::table('users')->where('id', $id)->update($userData);

            // 2. Kelola Data Anggota (Siswa)
            if ($request->role == 'siswa') {
                // updateOrInsert: Jika sudah ada di tabel anggota maka update, jika belum ada maka buat baru
                DB::table('anggota')->updateOrInsert(
                    ['user_id' => $id], // Pencarian berdasarkan user_id
                    [
                        'nis' => $request->nis,
                        'nama_anggota' => $request->nama,
                        'kelas' => $request->kelas,
                        'jurusan' => $request->jurusan,
                        'no_telp' => $request->no_telp,
                        'updated_at' => now(),
                    ]
                );
            } else {
                // Jika role diubah menjadi admin/petugas, hapus data anggotanya agar tidak duplikat
                DB::table('anggota')->where('user_id', $id)->delete();
            }
        });

        return back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Karena di database biasanya pakai Cascade Delete, 
        // menghapus User akan otomatis menghapus data di tabel Anggota
        DB::table('users')->where('id', $id)->delete();
        
        return back()->with('success', 'User berhasil dihapus!');
    }
}