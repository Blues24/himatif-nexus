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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->bigIncrements('kegiatan_id');
            $table->string('nama_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->date('tanggal_pelaksanaan');
            $table->string('status_kegiatan')->default('rencana');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
