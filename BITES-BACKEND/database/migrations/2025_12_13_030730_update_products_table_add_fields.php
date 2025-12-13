<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (!Schema::hasColumn('products', 'type')) {
                $table->enum('type', ['equipo', 'accesorio'])->after('price');
            }

            if (!Schema::hasColumn('products', 'color')) {
                $table->string('color')->nullable()->after('type');
            }

            if (!Schema::hasColumn('products', 'storage')) {
                $table->string('storage')->nullable()->after('color');
            }

            // image_url ya existe → NO se toca
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {

            if (Schema::hasColumn('products', 'type')) {
                $table->dropColumn('type');
            }

            if (Schema::hasColumn('products', 'color')) {
                $table->dropColumn('color');
            }

            if (Schema::hasColumn('products', 'storage')) {
                $table->dropColumn('storage');
            }
        });
    }
};