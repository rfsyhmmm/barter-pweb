<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('trades', function (Blueprint $table) {
        // Buat sender_item_id boleh kosong (untuk skenario Full Pay)
        $table->unsignedBigInteger('sender_item_id')->nullable()->change();
        
        // Tambahkan kolom untuk nominal dan pembayaran
        $table->integer('amount')->default(0)->after('receiver_item_id');
        $table->string('payment_method')->nullable()->after('amount'); // COD / Transfer
        $table->string('payment_proof')->nullable()->after('payment_method'); // Bukti TF
        
        // Perbarui daftar status untuk alur negosiasi dan escrow
        $table->enum('status', [
            'pending', 
            'negotiating', 
            'awaiting_payment', 
            'paid', 
            'completed', 
            'rejected'
        ])->default('pending')->change();
    });
}
};