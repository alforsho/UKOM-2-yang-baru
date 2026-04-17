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
            // 1. Primary Key
            $table->id('id_transaksi'); 

            // 2. Foreign Key ke tabel users (Siswa/Admin)
            $table->foreignId('id')->constrained('users')->onDelete('cascade');

            // 3. Foreign Key ke tabel buku (menggunakan id_buku sebagai referensi)
            $table->foreignId('id_buku')->constrained('buku', 'id_buku')->onDelete('cascade');

            // 4. Tanggal Peminjaman
            $table->dateTime('tanggal_peminjaman');

            // 5. Tanggal Pengembalian (Bisa dibuat nullable jika saat pinjam belum ditentukan)
            $table->dateTime('tanggal_pengembalian')->nullable();

            // 6. Status (Menambahkan default 'menunggu' untuk alur persetujuan admin)
            $table->string('status', 255)->default('menunggu');

            // 7. created_at & updated_at
            $table->timestamps();

            // 8. FITUR ARSIP: deleted_at (Penting untuk SoftDeletes)
            $table->softDeletes();
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