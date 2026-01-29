<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->foreignId('product_id')
                ->after('id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('image_path')->after('product_id');
            $table->boolean('is_primary')->default(false)->after('image_path');
        });
    }

    public function down()
    {
        Schema::table('product_images', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropColumn(['product_id', 'image_path', 'is_primary']);
        });
    }

};
