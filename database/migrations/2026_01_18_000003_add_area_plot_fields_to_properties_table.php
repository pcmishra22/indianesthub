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
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('super_builtup_area', 10, 2)->nullable();
            $table->decimal('builtup_area', 10, 2)->nullable();
            $table->decimal('carpet_area', 10, 2)->nullable();
            $table->string('area_unit', 10)->nullable();
            $table->decimal('plot_area', 10, 2)->nullable();
            $table->decimal('plot_length', 10, 2)->nullable();
            $table->decimal('plot_breadth', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'super_builtup_area',
                'builtup_area',
                'carpet_area',
                'area_unit',
                'plot_area',
                'plot_length',
                'plot_breadth',
            ]);
        });
    }
};
