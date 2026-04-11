<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        // 1. Query dasar: Join tabel users dan anggota
        $query = DB::table('users')
            ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
            ->select(
                'users.id', 
                'users.nama', 
                'users.username', 
                'users.role', 
                'anggota.nis', 
                'anggota.kelas', 
                'anggota.jurusan', 
                'anggota.no_telp'
            );

        // 2. Logika Filter Pencarian (Nama, Username, atau NIS)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.nama', 'like', "%$search%")
                  ->orWhere('users.username', 'like', "%$search%")
                  ->orWhere('anggota.nis', 'like', "%$search%");
            });
        }

        // 3. Filter Berdasarkan Role
        if ($request->filled('role')) {
            $query->where('users.role', $request->role);
        }

        // 4. Filter Berdasarkan Kelas
        if ($request->filled('kelas')) {
            $query->where('anggota.kelas', $request->kelas);
        }

        // Eksekusi query dengan urutan terbaru
        $users = $query->orderBy('users.id', 'desc')->get();

        return view('anggota.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'role'     => 'required'
        ]);

        DB::transaction(function () use ($request) {
            // Simpan ke tabel users
            $idUser = DB::table('users')->insertGetId([
                'nama'       => $request->nama,
                'username'   => $request->username,
                'password'   => Hash::make($request->password),
                'role'       => $request->role,
                'created_at' => now(),
            ]);

            // Jika role siswa, isi detail ke tabel anggota
            if ($request->role == 'siswa') {
                DB::table('anggota')->insert([
                    'user_id'      => $idUser,
                    'nis'          => $request->nis,
                    'nama_anggota' => $request->nama,
                    'kelas'        => $request->kelas,
                    'jurusan'      => $request->jurusan,
                    'no_telp'      => $request->no_telp,
                    'created_at'   => now(),
                ]);
            }
        });

        return back()->with('success', 'Data berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'role'     => 'required'
        ]);

        DB::transaction(function () use ($request, $id) {
            // 1. Update data dasar di tabel Users
            $userData = [
                'nama'       => $request->nama,
                'username'   => $request->username,
                'role'       => $request->role,
                'updated_at' => now(),
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            DB::table('users')->where('id', $id)->update($userData);

            // 2. Kondisi Sinkronisasi Tabel Anggota
            if ($request->role == 'siswa') {
                // Gunakan updateOrInsert jika sebelumnya dia admin lalu berubah jadi siswa
                DB::table('anggota')->updateOrInsert(
                    ['user_id' => $id],
                    [
                        'nis'          => $request->nis,
                        'nama_anggota' => $request->nama,
                        'kelas'        => $request->kelas,
                        'jurusan'      => $request->jurusan,
                        'no_telp'      => $request->no_telp,
                        'updated_at'   => now(),
                    ]
                );
            } else {
                // Jika role diubah dari siswa ke admin, hapus data anggotanya
                DB::table('anggota')->where('user_id', $id)->delete();
            }
        });

        return back()->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        // Menghapus di tabel users. 
        // Pastikan di database sudah set ON DELETE CASCADE pada foreign key user_id di tabel anggota.
        DB::table('users')->where('id', $id)->delete();
        
        return back()->with('success', 'Data berhasil dihapus!');
    }
}