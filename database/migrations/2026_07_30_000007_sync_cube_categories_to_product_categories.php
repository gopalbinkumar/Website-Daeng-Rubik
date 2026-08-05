<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('cube_categories') || !Schema::hasTable('product_categories')) {
            return;
        }

        $now = now();
        $existingMaxSort = (int) DB::table('product_categories')->max('sort_order');

        DB::table('cube_categories')
            ->orderBy('id')
            ->get()
            ->each(function ($category) use (&$existingMaxSort, $now) {
                $existing = DB::table('product_categories')->where('id', $category->id)->first();

                DB::table('product_categories')->updateOrInsert(
                    ['id' => $category->id],
                    [
                        'name' => $category->name,
                        'slug' => $category->slug ?: Str::slug($category->name),
                        'description' => $category->description ?? null,
                        'icon' => $category->icon ?? null,
                        'is_active' => true,
                        'sort_order' => $existing->sort_order ?? ++$existingMaxSort,
                        'created_at' => $existing->created_at ?? $now,
                        'updated_at' => $now,
                    ]
                );
            });
    }

    public function down(): void
    {
        // Master kategori produk bisa sudah dipakai produk, jadi rollback tidak menghapus data sinkronisasi.
    }
};
