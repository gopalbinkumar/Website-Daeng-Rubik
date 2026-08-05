<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'product_category_id')) {
                $table->unsignedBigInteger('product_category_id')->nullable()->after('condition');
            }
        });

        if (Schema::hasColumn('products', 'cube_category_id')) {
            DB::table('products')
                ->whereNull('product_category_id')
                ->update([
                    'product_category_id' => DB::raw('cube_category_id'),
                ]);
        }

        $fallbackCategoryId = DB::table('product_categories')
            ->where('name', 'Lainnya')
            ->value('id');

        if ($fallbackCategoryId) {
            DB::table('products')
                ->whereNull('product_category_id')
                ->update(['product_category_id' => $fallbackCategoryId]);

            DB::table('products')
                ->whereNotNull('product_category_id')
                ->whereNotIn('product_category_id', DB::table('product_categories')->select('id'))
                ->update(['product_category_id' => $fallbackCategoryId]);
        }

        $this->dropForeignKeysForColumn('cube_category_id');

        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'cube_category_id')) {
                $table->dropColumn('cube_category_id');
            }
        });

        if (!$this->hasForeignKeyForColumn('product_category_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreign('product_category_id')
                    ->references('id')
                    ->on('product_categories')
                    ->restrictOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'cube_category_id')) {
                $table->unsignedBigInteger('cube_category_id')->nullable()->after('condition');
            }
        });

        if (Schema::hasColumn('products', 'product_category_id')) {
            $this->dropForeignKeysForColumn('product_category_id');

            DB::table('products')
                ->whereNull('cube_category_id')
                ->update([
                    'cube_category_id' => DB::raw('product_category_id'),
                ]);

            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('product_category_id');
            });
        }
    }

    private function dropForeignKeysForColumn(string $column): void
    {
        foreach ($this->foreignKeysForColumn($column) as $constraint) {
            DB::statement(sprintf(
                'ALTER TABLE `products` DROP FOREIGN KEY `%s`',
                str_replace('`', '``', $constraint->CONSTRAINT_NAME)
            ));
        }
    }

    private function hasForeignKeyForColumn(string $column): bool
    {
        return count($this->foreignKeysForColumn($column)) > 0;
    }

    private function foreignKeysForColumn(string $column): array
    {
        return DB::select(
            "SELECT CONSTRAINT_NAME
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = 'products'
               AND COLUMN_NAME = ?
               AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$column]
        );
    }
};
