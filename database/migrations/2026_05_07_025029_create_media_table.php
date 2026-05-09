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
    Schema::create('media', function (Blueprint $table) {
        $table->uuid('id')->primary(); // Keamanan: Pake UUID biar ID gak urut (1, 2, 3)
        $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Pemilik file
        $table->string('file_name'); // Nama file yang sudah di-hash (aman)
        $table->string('original_name'); // Nama asli cuma buat pajangan
        $table->string('mime_type'); // Validasi tipe file (image/png, video/mp4, dll)
        $table->bigInteger('file_size'); // Ukuran file dalam bytes
        $table->text('description')->nullable(); 
        $table->enum('visibility', ['private', 'public'])->default('private');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
