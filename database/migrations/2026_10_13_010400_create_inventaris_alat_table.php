<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inventaris_alat', function (Blueprint $table) {
            $table->bigIncrements('inventaris_id');
            $table->string('nama_barang');
            $table->integer('stok')->default(0);
            $table->string('kondisi')->default('baik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris_alat');
    }
};