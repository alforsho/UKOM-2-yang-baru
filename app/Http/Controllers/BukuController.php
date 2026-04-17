<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // --- FITUR KATALOG DEMO (Akses Publik) ---
    public function indexDemo(Request $request)
    {
        $query = Buku::query();

        // Fitur Pencarian di Halaman Demo
        $query->when($request->search, function ($q) use ($request) {
            return $q->where('nama_buku', 'like', '%' . $request->search . '%')
                     ->orWhere('penerbit', 'like', '%' . $request->search . '%');
        });

        // Ambil data buku untuk ditampilkan di dashboard/demo.blade.php
        $books = $query->latest()->get();

        return view('dashboard.demo', compact('books'));
    }

    // --- FITUR KELOLA BUKU (Akses Admin) ---
    public function index(Request $request)
    {
        $query = Buku::query();

        // 1. Filter Pencarian Nama Buku
        $query->when($request->search, function ($q) use ($request) {
            return $q->where('nama_buku', 'like', '%' . $request->search . '%');
        });

        // 2. Filter Kondisi Stok
        if ($request->kondisi == 'stok_cukup') {
            $query->where('stok', '>', 5);
        } elseif ($request->kondisi == 'stok_menipis') {
            $query->whereBetween('stok', [1, 5]);
        } elseif ($request->kondisi == 'stok_habis') {
            $query->where('stok', '<=', 0);
        }

        // Urutkan dari yang terbaru ditambahkan (Hanya yang tidak terhapus)
        $bukus = $query->latest()->get();

        return view('buku.index', compact('bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_buku' => 'required',
            'penerbit' => 'required',
            'stok' => 'required|numeric',
        ]);

        Buku::create($request->all());
        return back()->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        // Mendukung pencarian data termasuk yang diarsip (untuk keperluan restore/view)
        return response()->json(Buku::withTrashed()->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_buku' => 'required',
            'penerbit' => 'required',
            'stok' => 'required|numeric',
        ]);

        Buku::withTrashed()->findOrFail($id)->update($request->all());
        return back()->with('success', 'Data buku berhasil diperbarui!');
    }

    // --- FITUR ARSIP & DELETE ---

    // 1. Soft Delete (Masuk ke Arsip)
    public function destroy($id)
    {
        Buku::destroy($id);
        return back()->with('success', 'Buku berhasil dipindahkan ke arsip!');
    }

    // 2. Tampilkan Halaman Arsip Buku
    public function arsip()
    {
        $arsip = Buku::onlyTrashed()->latest()->get();
        return view('buku.arsip', compact('arsip'));
    }

    // 3. Restore Buku (Kembalikan dari Arsip)
    public function restore($id)
    {
        $buku = Buku::onlyTrashed()->findOrFail($id);
        $buku->restore();

        return back()->with('success', 'Buku berhasil dikembalikan dari arsip!');
    }

    // 4. Force Delete (Hapus Permanen)
    public function forceDelete($id)
    {
        $buku = Buku::onlyTrashed()->findOrFail($id);
        $buku->forceDelete();

        return back()->with('success', 'Buku telah dihapus permanen dari sistem!');
    }
}