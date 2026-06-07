<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            // Menghubungkan postingan dengan ID user yang mengunggahnya
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image'); // Menyimpan path file foto interior
            $table->text('caption')->nullable(); // Kolom teks deskripsi/caption
            $table->integer('likes_count')->default(0); // Status jumlah suka default 0
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};