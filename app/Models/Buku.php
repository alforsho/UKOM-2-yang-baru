<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model // Pastikan di sini tulisannya 'Buku', BUKAN 'BukuController'
{
    use HasFactory;

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    public $timestamps = false;

    protected $fillable = [
        'nama_buku', 
        'penerbit', 
        'stok'
    ];
}