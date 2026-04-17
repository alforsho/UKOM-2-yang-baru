<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    /**
     * TAMPILAN UTAMA & FILTER
     */
    public function index(Request $request)
    {
        $query = DB::table('users')
            ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
            ->select(
                'users.id', 
                'users.nama', 
                'users.username', 
                'users.role', 
                'anggota.nis', 
                'anggota.kelas', 
                'anggota.jurusan'
            );

        // Pencarian Berdasarkan Nama, Username, atau NIS
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('users.nama', 'like', "%$search%")
                  ->orWhere('users.username', 'like', "%$search%")
                  ->orWhere('anggota.nis', 'like', "%$search%");
            });
        }

        // Filter Role (Admin/Siswa)
        if ($request->filled('role')) {
            $query->where('users.role', $request->role);
        }

        $users = $query->orderBy('users.id', 'desc')->get();
        return view('anggota.index', compact('users'));
    }

    /**
     * DETAIL ANGGOTA & CETAK PROFIL
     * Menangani view dari sisi Admin maupun sisi Profil Siswa
     */
    public function show(Request $request, $id)
    {
        // Mengambil data profil gabungan
        $anggota = DB::table('users')
            ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
            ->select(
                'users.id', 
                'users.nama', 
                'users.username', 
                'users.role', 
                'users.created_at',
                'anggota.nis', 
                'anggota.kelas', 
                'anggota.jurusan', 
                'anggota.no_telp'
            )
            ->where('users.id', $id)
            ->first();

        if (!$anggota) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        
        // Contoh: /admin/anggota/show/5?from=profil
        $redirect = $request->query('from', 'admin'); 

        // Mengirim variabel anggota dan redirect ke view yang sama
        return view('anggota.show', compact('anggota', 'redirect'));
    }

    /**
     * SIMPAN DATA BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:users',
            'password' => 'required',
            'role'     => 'required'
        ]);

        DB::transaction(function () use ($request) {
            // 1. Simpan ke tabel Users
            $idUser = DB::table('users')->insertGetId([
                'nama'       => $request->nama,
                'username'   => $request->username,
                'password'   => Hash::make($request->password),
                'role'       => $request->role,
                'created_at' => now(),
            ]);

            // 2. Jika role adalah siswa, simpan ke tabel Anggota
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

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * UPDATE DATA
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'     => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'role'     => 'required'
        ]);

        DB::transaction(function () use ($request, $id) {
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

            if ($request->role == 'siswa') {
                DB::table('anggota')->updateOrInsert(
                    ['user_id' => $id],
                    [
                        'nis'        => $request->nis,
                        'kelas'      => $request->kelas,
                        'jurusan'    => $request->jurusan,
                        'no_telp'    => $request->no_telp,
                        'updated_at' => now(),
                    ]
                );
            } else {
                // Jika diubah jadi admin, hapus record di tabel anggota
                DB::table('anggota')->where('user_id', $id)->delete();
            }
        });

        return back()->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * HAPUS DATA
     */
    public function destroy($id)
    {
        DB::table('anggota')->where('user_id', $id)->delete();
        DB::table('users')->where('id', $id)->delete();

        return back()->with('success', 'Data berhasil dihapus!');
    }
}