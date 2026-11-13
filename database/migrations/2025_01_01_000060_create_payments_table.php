<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('transaction_id')->nullable();
            $table->string('payment_type', 50)->nullable();
            $table->string('transaction_status', 30)->index();
            $table->string('fraud_status', 30)->nullable();
            $table->unsignedBigInteger('gross_amount');
            // JSON mentah disimpan untuk audit dan debugging notifikasi Midtrans.
            $table->json('raw_notification')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
