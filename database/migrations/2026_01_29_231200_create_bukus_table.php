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
            // 1. Primary Key: id_buku
            $table->id('id_buku'); 

            // 2. Atribut Buku
            $table->string('nama_buku'); 
            $table->string('penerbit'); 
            $table->integer('stok'); 

            // 3. Timestamps (created_at & updated_at)
            // Meskipun di Model diset false, sangat disarankan tetap ada di DB untuk audit
            $table->timestamps();

            // 4. FITUR ARSIP: deleted_at (WAJIB untuk SoftDeletes)
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
        Schema::dropIfExists('buku'); // Pastikan nama tabel sinkron 'buku', bukan 'bukus'
    }
}