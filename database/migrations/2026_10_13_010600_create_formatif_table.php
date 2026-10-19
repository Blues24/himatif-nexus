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
        Schema::create('formatif', function (Blueprint $table) {
            $table->bigIncrements('formatif_id');
            // user_id is a UUID, so this column must not be a bigint.
            $table->uuid('user_id')->index();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->string('kategori');
            $table->longText('text_konten');
            $table->string('thumbnail_path')->nullable();
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formatif');
    }
};