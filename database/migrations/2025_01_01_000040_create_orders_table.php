<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->string('order_code', 50)->unique();
            $table->string('recipient_name', 100);
            $table->string('phone', 20);
            $table->text('shipping_address');
            $table->unsignedBigInteger('total_amount');
            // String dipilih atas enum agar status bisa ditambah tanpa ALTER TABLE.
            $table->string('payment_status', 20)->default('pending')->index();
            $table->string('order_status', 20)->default('pending')->index();
            $table->string('snap_token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
