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
            // 1. Primary Key: id_transaksi (bigint unsigned, auto_increment)
            $table->id('id_transaksi'); 

            // 2. Foreign Key: id (bigint unsigned) menghubungkan ke tabel users
            $table->foreignId('id')->constrained('users')->onDelete('cascade');

            // 3. Foreign Key: id_buku (bigint unsigned) menghubungkan ke tabel buku
            $table->foreignId('id_buku')->constrained('buku', 'id_buku')->onDelete('cascade');

            // 4. tanggal_peminjaman (datetime) sesuai di foto
            $table->dateTime('tanggal_peminjaman');

            // 5. tanggal_pengembalian (datetime) sesuai di foto
            $table->dateTime('tanggal_pengembalian');

            // 6. status (varchar 255)
            $table->string('status', 255);

            // 7 & 8. created_at & updated_at (timestamp, nullable)
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
        Schema::dropIfExists('transaksi');
    }
}