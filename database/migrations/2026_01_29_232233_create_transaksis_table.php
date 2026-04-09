<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('transaksi', function (Blueprint $table) {
        $table->id('id_transaksi'); // Atribut id_transaksi
        $table->foreignId('id')->constrained('users')->onDelete('cascade'); // Relasi ke User
        $table->foreignId('id_buku')->constrained('buku', 'id_buku')->onDelete('cascade'); // Relasi ke Buku
        $table->date('tanggal_trs'); // Atribut tanggal_trs
        $table->string('status'); // Atribut status (misal: Pinjam/Kembali)
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
        Schema::dropIfExists('transaksis');
    }
}
