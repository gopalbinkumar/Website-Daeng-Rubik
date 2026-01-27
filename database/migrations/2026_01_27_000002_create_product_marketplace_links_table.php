<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_marketplace_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('marketplace', ['tokopedia', 'shopee', 'tiktok_shop']);
            $table->string('url');
            $table->timestamps();

            $table->unique(['product_id', 'marketplace']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_marketplace_links');
    }
};

