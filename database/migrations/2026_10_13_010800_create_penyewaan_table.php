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
        Schema::create('penyewaan_alats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventaris_alat_id');
            $table->uuid('user_id');
            $table->integer('jumlah_pinjam');
            $table->date('tanggal_pinjam');
            $table->date('tanggal_kembali')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('inventaris_alat_id')->references('inventaris_id')->on('inventaris_alat')->cascadeOnDelete();
            $table->foreign('user_id')->references('user_uuid')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyewaan_alats');
    }
};
