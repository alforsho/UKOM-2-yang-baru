<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // Tampil Data
    public function index()
    {
        $bukus = Buku::all();
        return view('buku.index', compact('bukus'));
    }

    // Simpan Data Baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_buku' => 'required',
            'penerbit' => 'required',
            'stok' => 'required|numeric',
        ]);

        Buku::create([
            'nama_buku' => $request->nama_buku,
            'penerbit' => $request->penerbit,
            'stok' => $request->stok,
        ]);

        return back()->with('success', 'Buku berhasil ditambahkan!');
    }

    // Ambil Data untuk Edit (JSON)
    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        return response()->json($buku);
    }

    // Update Data
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_buku' => 'required',
            'penerbit' => 'required',
            'stok' => 'required|numeric',
        ]);

        $buku = Buku::findOrFail($id);
        $buku->update($request->all());

        return back()->with('success', 'Data buku berhasil diperbarui!');
    }

    // Hapus Data
    public function destroy($id)
    {
        Buku::destroy($id);
        return back()->with('success', 'Buku berhasil dihapus!');
    }
}