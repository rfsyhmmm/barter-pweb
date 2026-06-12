<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('reporter_id')->constrained('users')->onDelete('cascade'); // Pelapor
        $table->foreignId('reported_user_id')->nullable()->constrained('users')->onDelete('cascade'); // User yang dilaporkan
        $table->foreignId('item_id')->nullable()->constrained()->onDelete('cascade'); // Barang yang dilaporkan
        $table->string('reason'); // Kategori alasan (Fraud, Spam, Inappropriate)
        $table->text('description')->nullable(); // Penjelasan detail pelapor
        $table->enum('status', ['pending', 'resolved', 'dismissed'])->default('pending');
        $table->timestamps();
    });
}
};