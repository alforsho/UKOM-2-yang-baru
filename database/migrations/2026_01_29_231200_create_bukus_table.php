<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBukusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
public function up()
{
    Schema::create('buku', function (Blueprint $table) {
        $table->id('id_buku'); // Atribut id_buku
        $table->string('nama_buku'); // Atribut nama_buku
        $table->string('penerbit'); // Atribut penerbit
        $table->integer('stok'); // Atribut stok
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
        Schema::dropIfExists('bukus');
    }
}
