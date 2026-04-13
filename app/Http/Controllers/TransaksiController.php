<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF; // Import library DomPDF

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $bukus = DB::table('buku')->where('stok', '>', 0)->get();
        $list_anggota = DB::table('users')->where('role', 'siswa')->get();

        $harga_denda_per_hari = 1000; 

        $query = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'users.nama') 
            ->orderBy('transaksi.id_transaksi', 'desc');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('users.nama', 'like', "%$search%")
                  ->orWhere('buku.nama_buku', 'like', "%$search%");
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('transaksi.tanggal_peminjaman', $request->get('tanggal'));
        }

        if ($user->role == 'admin') {
            $transaksis = $query->get();
        } else {
            $transaksis = $query->where('transaksi.id', $user->id)->get();
        }

        foreach ($transaksis as $t) {
            $this->hitungDenda($t);
        }

        if ($user->role == 'admin') {
            return view('transaksi.admin', compact('transaksis', 'bukus', 'list_anggota'));
        } else {
            return view('transaksi.siswa', compact('transaksis', 'bukus'));
        }
    }

    // Fungsi tambahan untuk menghitung denda agar tidak duplikasi code
    private function hitungDenda($t)
    {
        $tgl_deadline = Carbon::parse($t->tanggal_pengembalian);
        $tgl_akhir = ($t->status == 'Dikembalikan') 
                     ? Carbon::parse($t->updated_at) 
                     : Carbon::now();

        if ($tgl_akhir->gt($tgl_deadline)) {
            $selisih_hari = $tgl_akhir->diffInDays($tgl_deadline);
            $t->total_denda = $selisih_hari * 1000;
            $t->terlambat = $selisih_hari;
        } else {
            $t->total_denda = 0;
            $t->terlambat = 0;
        }
    }

    public function show($id)
    {
        $transaksi = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'buku.penerbit', 'users.nama')
            ->where('transaksi.id_transaksi', $id)
            ->first();

        if (!$transaksi) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        $this->hitungDenda($transaksi);

        return view('transaksi.detail', compact('transaksi'));
    }

    // --- FITUR CETAK PDF ---

    // 1. Cetak Satuan (Detail Transaksi)
    public function cetak_pdf($id)
    {
        $transaksi = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'buku.penerbit', 'users.nama')
            ->where('transaksi.id_transaksi', $id)
            ->first();

        if (!$transaksi) return back()->with('error', 'Data tidak ditemukan.');

        $this->hitungDenda($transaksi);

        $pdf = PDF::loadView('transaksi.pdf_detail', compact('transaksi'));
        return $pdf->stream('Struk_Transaksi_'.$id.'.pdf');
    }

    // 2. Cetak Semua (Laporan Admin)
    public function cetak_semua()
    {
        $transaksis = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'users.nama')
            ->orderBy('transaksi.id_transaksi', 'desc')
            ->get();

        foreach ($transaksis as $t) {
            $this->hitungDenda($t);
        }

        $pdf = PDF::loadView('transaksi.pdf_all', compact('transaksis'));
        return $pdf->download('Laporan_Transaksi_Perpustakaan.pdf');
    }

    // --- LANJUTAN FUNGSI CRUD BAWAAN ---

    public function store(Request $request)
    {
        $userId = auth()->user()->role == 'admin' ? $request->user_id : auth()->id();

        $cekPinjam = DB::table('transaksi')->where('id', $userId)->where('status', 'Dipinjam')->count();
        if ($cekPinjam >= 3) {
            return back()->with('error', 'Gagal! Siswa ini masih meminjam 3 buku.');
        }

        $buku = DB::table('buku')->where('id_buku', $request->id_buku)->first();
        if (!$buku || $buku->stok <= 0) {
            return back()->with('error', 'Maaf, stok buku ini sudah habis!');
        }

        DB::transaction(function () use ($request, $userId) {
            DB::table('transaksi')->insert([
                'id' => $userId,
                'id_buku' => $request->id_buku,
                'tanggal_peminjaman' => now(),
                'tanggal_pengembalian' => now()->addDays(7),
                'status' => 'Dipinjam',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            DB::table('buku')->where('id_buku', $request->id_buku)->decrement('stok');
        });

        return back()->with('success', 'Peminjaman berhasil dicatat!');
    }

    public function edit($id)
    {
        $transaksi = DB::table('transaksi')->where('id_transaksi', $id)->first();
        $bukus = DB::table('buku')->get();
        $list_anggota = DB::table('users')->where('role', 'siswa')->get();
        return view('transaksi.edit', compact('transaksi', 'bukus', 'list_anggota'));
    }

    public function update(Request $request, $id)
    {
        DB::table('transaksi')->where('id_transaksi', $id)->update([
            'id' => $request->user_id,
            'id_buku' => $request->id_buku,
            'status' => $request->status,
            'updated_at' => now(),
        ]);
        return redirect('/transaksi')->with('success', 'Transaksi diperbarui!');
    }

    public function kembali($id)
    {
        $tr = DB::table('transaksi')->where('id_transaksi', $id)->first();
        if ($tr && $tr->status == 'Dipinjam') {
            DB::transaction(function () use ($tr, $id) {
                DB::table('transaksi')->where('id_transaksi', $id)->update([
                    'status' => 'Dikembalikan',
                    'updated_at' => now() 
                ]);
                DB::table('buku')->where('id_buku', $tr->id_buku)->increment('stok');
            });
            return back()->with('success', 'Buku telah dikembalikan.');
        }
        return back()->with('error', 'Buku sudah dikembalikan.');
    }

    public function destroy($id)
    {
        $transaksi = DB::table('transaksi')->where('id_transaksi', $id)->first();
        if ($transaksi && $transaksi->status == 'Dipinjam') {
            return back()->with('error', 'Buku masih dipinjam!');
        }
        DB::table('transaksi')->where('id_transaksi', $id)->delete();
        return back()->with('success', 'Data dihapus.');
    }
}