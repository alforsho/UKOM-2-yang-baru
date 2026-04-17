<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF; 

class TransaksiController extends Controller
{
    /**
     * 1. MONITORING UNTUK ADMIN (INDEX)
     * FITUR FILTER: Pencarian (Nama/Buku) dan Waktu (Tgl/Bln/Thn)
     * Menambahkan filter whereNull agar data yang diarsip tidak tampil di sini.
     */
    public function index(Request $request)
    {
        $bukus = DB::table('buku')->where('stok', '>', 0)->get();
        $list_anggota = DB::table('users')->where('role', 'siswa')->get();

        $query = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'users.nama') 
            ->whereNull('transaksi.deleted_at') // Filter data aktif saja
            ->orderBy('transaksi.id_transaksi', 'desc');

        // Filter Pencarian Nama atau Judul Buku
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('users.nama', 'like', "%$search%")
                  ->orWhere('buku.nama_buku', 'like', "%$search%");
            });
        }

        // Filter Waktu Berdasarkan Tanggal Peminjaman
        if ($request->filled('tgl')) $query->whereDay('transaksi.tanggal_peminjaman', $request->tgl);
        if ($request->filled('bln')) $query->whereMonth('transaksi.tanggal_peminjaman', $request->bln);
        if ($request->filled('thn')) $query->whereYear('transaksi.tanggal_peminjaman', $request->thn);

        $transaksis = $query->get();
        foreach ($transaksis as $t) { $this->hitungDenda($t); }

        return view('transaksi.admin', compact('transaksis', 'bukus', 'list_anggota'));
    }

    /**
     * 2. DETAIL TRANSAKSI (SHOW)
     */
    public function show($id)
    {
        $transaksi = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->leftJoin('anggota', 'users.id', '=', 'anggota.user_id')
            ->select(
                'transaksi.*', 
                'buku.nama_buku', 
                'buku.penerbit', 
                'users.nama', 
                'anggota.nama_anggota', 
                'anggota.nis', 
                'anggota.kelas', 
                'anggota.jurusan'
            )
            ->where('transaksi.id_transaksi', $id)
            ->first();

        if (!$transaksi) return back()->with('error', 'Data tidak ditemukan.');
        $this->hitungDenda($transaksi);
        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * 3. EDIT TRANSAKSI
     */
    public function edit($id)
    {
        $transaksi = DB::table('transaksi')->where('id_transaksi', $id)->first();
        $bukus = DB::table('buku')->get();
        $users = DB::table('users')->where('role', 'siswa')->get();

        if (!$transaksi) return back()->with('error', 'Data tidak ditemukan.');
        return view('transaksi.edit', compact('transaksi', 'bukus', 'users'));
    }

    /**
     * 4. UPDATE TRANSAKSI
     */
    public function update(Request $request, $id)
    {
        DB::table('transaksi')->where('id_transaksi', $id)->update([
            'id_buku' => $request->id_buku,
            'id' => $request->id,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian' => $request->tanggal_pengembalian,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * 5. KATALOG BUKU (SISWA)
     */
    public function pinjamBuku(Request $request)
    {
        $query = DB::table('buku')->where('stok', '>', 0);
        if ($request->filled('search')) {
            $query->where('nama_buku', 'like', '%' . $request->search . '%');
        }
        $bukus = $query->get();
        return view('transaksi.pinjam', compact('bukus'));
    }

    /**
     * 6. RIWAYAT PINJAMAN (SISWA)
     */
    public function riwayatBuku()
    {
        $user = auth()->user();
        $transaksis = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->select('transaksi.*', 'buku.nama_buku')
            ->where('transaksi.id', $user->id)
            ->whereNull('transaksi.deleted_at')
            ->orderBy('transaksi.id_transaksi', 'desc')
            ->get();

        foreach ($transaksis as $t) { $this->hitungDenda($t); }
        return view('transaksi.riwayat', compact('transaksis'));
    }

    /**
     * 7. LOGIKA HITUNG DENDA
     */
    private function hitungDenda($t)
    {
        $tgl_deadline = Carbon::parse($t->tanggal_pengembalian);
        $tgl_akhir = ($t->status == 'Dikembalikan') ? Carbon::parse($t->updated_at) : Carbon::now();

        if ($tgl_akhir->gt($tgl_deadline)) {
            $selisih_hari = $tgl_akhir->diffInDays($tgl_deadline);
            $t->total_denda = $selisih_hari * 1000;
            $t->terlambat = $selisih_hari;
        } else {
            $t->total_denda = 0;
            $t->terlambat = 0;
        }
    }

    /**
     * 8. SIMPAN TRANSAKSI (STORE)
     */
    public function store(Request $request)
    {
        $userId = auth()->user()->role == 'admin' ? $request->id : auth()->id();
        
        $cekPinjam = DB::table('transaksi')
            ->where('id', $userId)
            ->whereIn('status', ['Dipinjam', 'Pending'])
            ->whereNull('deleted_at')
            ->count();

        if ($cekPinjam >= 3) return back()->with('error', 'Batas maksimal 3 buku!');

        $buku = DB::table('buku')->where('id_buku', $request->id_buku)->first();
        if (!$buku || $buku->stok <= 0) return back()->with('error', 'Stok buku habis!');

        DB::table('transaksi')->insert([
            'id' => $userId,
            'id_buku' => $request->id_buku,
            'tanggal_peminjaman' => now(),
            'tanggal_pengembalian' => now()->addDays(7),
            'status' => 'Pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return (auth()->user()->role == 'admin') 
                ? redirect('transaksi')->with('success', 'Permintaan pinjam masuk ke daftar Pending.') 
                : redirect()->route('transaksi.riwayat')->with('success', 'Permintaan pinjam berhasil dikirim, menunggu persetujuan admin!');
    }

    /**
     * 9. PERSETUJUAN ADMIN (SETUJUI)
     */
    public function setujui($id)
    {
        $tr = DB::table('transaksi')->where('id_transaksi', $id)->first();

        if ($tr && $tr->status == 'Pending') {
            $buku = DB::table('buku')->where('id_buku', $tr->id_buku)->first();
            if (!$buku || $buku->stok <= 0) return back()->with('error', 'Stok buku habis!');

            DB::transaction(function () use ($tr, $id) {
                DB::table('transaksi')->where('id_transaksi', $id)->update([
                    'status' => 'Dipinjam',
                    'updated_at' => now()
                ]);
                DB::table('buku')->where('id_buku', $tr->id_buku)->decrement('stok');
            });

            return back()->with('success', 'Peminjaman disetujui!');
        }
        return back()->with('error', 'Data tidak valid.');
    }

    /**
     * 10. PENGEMBALIAN BUKU (KEMBALI)
     */
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
            return back()->with('success', 'Buku telah kembali.');
        }
        return back()->with('error', 'Data tidak valid.');
    }

    /**
     * 11. CETAK SEMUA LAPORAN (DENGAN FILTER)
     */
    public function cetak_semua(Request $request)
    {
        $query = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'users.nama')
            ->whereNull('transaksi.deleted_at');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('users.nama', 'like', "%$search%")
                  ->orWhere('buku.nama_buku', 'like', "%$search%");
            });
        }
        if ($request->filled('tgl')) $query->whereDay('transaksi.tanggal_peminjaman', $request->tgl);
        if ($request->filled('bln')) $query->whereMonth('transaksi.tanggal_peminjaman', $request->bln);
        if ($request->filled('thn')) $query->whereYear('transaksi.tanggal_peminjaman', $request->thn);

        $data = $query->orderBy('transaksi.tanggal_peminjaman', 'asc')->get();
        foreach ($data as $d) { $this->hitungDenda($d); }

        $pdf = PDF::loadView('transaksi.pdf_laporan', compact('data'));
        return $pdf->download('laporan_transaksi.pdf');
    }

    /**
     * 12. CETAK STRUK SATUAN
     */
    public function cetak_pdf($id)
    {
        $transaksi = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'users.nama')
            ->where('transaksi.id_transaksi', $id)->first();

        if (!$transaksi) return back()->with('error', 'Data tidak ditemukan.');
        if ($transaksi->status == 'Pending') return back()->with('error', 'Struk belum tersedia.');

        $this->hitungDenda($transaksi);
        $pdf = PDF::loadView('transaksi.pdf_detail', compact('transaksi'));
        $pdf->setPaper([0, 0, 450, 650], 'portrait'); 
        return $pdf->stream('Struk_'.$id.'.pdf');
    }

    /**
     * 13. HAPUS DATA (DESTROY)
     * Tetap dipertahankan agar fungsi delete di view tidak rusak.
     */
    public function destroy($id)
    {
        $tr = DB::table('transaksi')->where('id_transaksi', $id)->first();
        if ($tr && $tr->status == 'Dipinjam') {
            return back()->with('error', 'Gagal! Buku masih dipinjam.');
        }
        DB::table('transaksi')->where('id_transaksi', $id)->delete();
        return back()->with('success', 'Data transaksi telah dihapus.');
    }

    /**
     * 14. FITUR ARSIP
     */
    public function arsip()
    {
        $arsip = DB::table('transaksi')
            ->join('buku', 'transaksi.id_buku', '=', 'buku.id_buku')
            ->join('users', 'transaksi.id', '=', 'users.id')
            ->select('transaksi.*', 'buku.nama_buku', 'users.nama')
            ->whereNotNull('transaksi.deleted_at')
            ->orderBy('transaksi.deleted_at', 'desc')
            ->get();

        return view('transaksi.arsip', compact('arsip'));
    }

    /**
     * 15. PROSES PINDAH KE ARSIP (SOFT DELETE)
     */
    public function masukArsip($id)
    {
        $tr = DB::table('transaksi')->where('id_transaksi', $id)->first();
        if ($tr && $tr->status == 'Dipinjam') {
            return back()->with('error', 'Gagal! Selesaikan pengembalian dulu sebelum diarsip.');
        }

        DB::table('transaksi')->where('id_transaksi', $id)->update([
            'deleted_at' => now()
        ]);

        return back()->with('success', 'Data berhasil dipindahkan ke arsip.');
    }

    /**
     * 16. RESTORE DARI ARSIP
     */
    public function restore($id)
    {
        DB::table('transaksi')->where('id_transaksi', $id)->update([
            'deleted_at' => null
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Data berhasil dipulihkan!');
    }
}