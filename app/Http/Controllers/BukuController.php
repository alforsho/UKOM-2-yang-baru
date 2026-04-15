<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class BukuController extends Controller
{
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

        // Urutkan dari yang terbaru ditambahkan
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
        return response()->json(Buku::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_buku' => 'required',
            'penerbit' => 'required',
            'stok' => 'required|numeric',
        ]);

        Buku::findOrFail($id)->update($request->all());
        return back()->with('success', 'Data buku berhasil diperbarui!');
    }

    public function destroy($id)
    {
        Buku::destroy($id);
        return back()->with('success', 'Buku berhasil dihapus!');
    }
}