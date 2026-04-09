<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Anggota extends Model
{
    protected $table = 'anggota';
    protected $primaryKey = 'id_anggota';
    protected $fillable = ['user_id', 'nis', 'nama_anggota', 'kelas', 'jurusan', 'no_telp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}