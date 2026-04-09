<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnggotaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anggota', function (Blueprint $table) {
            $table->id('id_anggota');
            // Menghubungkan ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nis')->unique();
            $table->string('nama_anggota');
            
            // Perubahan ke ENUM
            $table->enum('kelas', ['10', '11', '12']);
            $table->enum('jurusan', ['RPL', 'TKJ', 'MM', 'DKV', 'SIJA', 'AKL', 'OTKP']);
            
            $table->string('no_telp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('anggota');
    }
}