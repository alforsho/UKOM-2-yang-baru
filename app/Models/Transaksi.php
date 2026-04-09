<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $primaryKey = 'id_transaksi';

    // Update bagian fillable sesuai dengan nama kolom baru di database
    protected $fillable = [
        'id', // Sesuai screenshot, kolom user menggunakan 'id' bukan 'user_id'
        'id_buku', 
        'tanggal_peminjaman', // Nama baru
        'tanggal_pengembalian', // Kolom tambahan baru
        'status'
    ];

    public function user()
    {
        // Berdasarkan gambar phpMyAdmin Anda, kolom foreign key adalah 'id'
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'id_buku', 'id_buku');
    }
}