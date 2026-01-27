<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cart_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('source', ['website', 'shopee', 'tokopedia', 'tiktok_shop'])->default('website');
            $table->enum('status', ['pending', 'paid', 'failed', 'cancelled'])->default('pending');

            // Shipping & receiver info
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->text('receiver_address');
            $table->string('receiver_postal_code', 20);

            $table->enum('shipping_zone', ['makassar', 'sulsel', 'luar_provinsi']);
            $table->string('shipping_city')->nullable();
            $table->string('shipping_province')->nullable();

            // Monetary fields (store in smallest currency unit: rupiah integer)
            $table->unsignedBigInteger('subtotal_amount');
            $table->unsignedBigInteger('shipping_cost');
            $table->unsignedBigInteger('total_amount');

            // Payment info
            $table->string('payment_method')->default('bank_transfer');
            $table->string('payment_bank_name')->nullable();
            $table->string('payment_account_name')->nullable();
            $table->string('payment_account_number')->nullable();
            $table->string('payment_proof_path')->nullable(); // uploaded image path

            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

