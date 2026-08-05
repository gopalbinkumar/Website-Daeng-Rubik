<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        foreach (['MoYu', 'GAN', 'QiYi', 'YJ', 'Lainnya'] as $index => $name) {
            DB::table('product_brands')->updateOrInsert(
                ['name' => $name],
                [
                    'slug' => Str::slug($name),
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }

        $categories = [
            ['id' => 18, 'name' => '2x2'],
            ['id' => 1, 'name' => '3x3'],
            ['id' => 2, 'name' => '4x4'],
            ['id' => 3, 'name' => '5x5'],
            ['id' => 20, 'name' => 'Pyraminx'],
            ['id' => 6, 'name' => 'Megaminx'],
            ['id' => 24, 'name' => 'Lainnya'],
        ];

        foreach ($categories as $index => $category) {
            DB::table('product_categories')->updateOrInsert(
                ['id' => $category['id']],
                [
                    'name' => $category['name'],
                    'slug' => Str::slug($category['name']),
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        DB::table('product_brands')
            ->whereIn('name', ['MoYu', 'GAN', 'QiYi', 'YJ', 'Lainnya'])
            ->delete();

        DB::table('product_categories')
            ->whereIn('name', ['2x2', '3x3', '4x4', '5x5', 'Pyraminx', 'Megaminx', 'Lainnya'])
            ->delete();
    }
};
