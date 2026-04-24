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
            if (!Schema::hasColumn('properties', 'expected_price')) {
                $table->decimal('expected_price', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('properties', 'price_per_sqft')) {
                $table->decimal('price_per_sqft', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('properties', 'negotiable')) {
                $table->boolean('negotiable')->default(false);
            }
            if (!Schema::hasColumn('properties', 'maintenance_charges')) {
                $table->decimal('maintenance_charges', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('properties', 'booking_amount')) {
                $table->decimal('booking_amount', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('properties', 'monthly_rent')) {
                $table->decimal('monthly_rent', 15, 2)->nullable();
            }
            if (!Schema::hasColumn('properties', 'lease_duration')) {
                $table->string('lease_duration')->nullable();
            }
            if (!Schema::hasColumn('properties', 'possession_status')) {
                $table->string('possession_status')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $columns = [
                'expected_price',
                'price_per_sqft',
                'negotiable',
                'maintenance_charges',
                'booking_amount',
                'monthly_rent',
                'lease_duration',
                'possession_status',
            ];
            foreach ($columns as $col) {
                if (Schema::hasColumn('properties', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
