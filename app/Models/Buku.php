<?php

namespace App\Http\Controllers; // Ini seharusnya di App\Models jika ini file Model
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Tambahkan ini

class Buku extends Model
{
    use HasFactory, SoftDeletes; // 2. Gunakan trait SoftDeletes di sini

    protected $table = 'buku';
    protected $primaryKey = 'id_buku';
    
    // Jika kamu ingin mencatat kapan data dihapus, aktifkan timestamps
    // atau jika tetap ingin false, pastikan migration kamu punya kolom deleted_at
    public $timestamps = false; 

    protected $fillable = [
        'nama_buku', 
        'penerbit', 
        'stok'
    ];

    // 3. Tambahkan ini jika kamu mematikan $timestamps tapi ingin Soft Delete jalan
    protected $dates = ['deleted_at'];
}