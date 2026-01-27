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
        Schema::create('learning_materials', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            // Level: basic / intermediate / advanced
            $table->enum('level', ['basic', 'intermediate', 'advanced'])->default('basic');

            // Kategori puzzle (3x3, 4x4, 5x5, megaminx, dsb)
            $table->string('category')->nullable();

            // Sumber video: YouTube / file lokal / lainnya
            $table->string('video_provider')->default('youtube'); // youtube|local|other
            $table->string('video_url')->nullable();
            $table->string('video_path')->nullable(); // untuk file lokal yang diupload

            // Metadata konten
            $table->unsignedInteger('duration_seconds')->default(0); // durasi video dalam detik
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedTinyInteger('rating')->default(0); // 0-5

            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('position')->default(0); // urutan tampilan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_materials');
    }
};
