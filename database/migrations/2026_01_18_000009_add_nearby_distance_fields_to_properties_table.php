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
            if (!Schema::hasColumn('properties', 'nearby_schools')) {
                $table->string('nearby_schools')->nullable()->after('verified_user');
            }
            if (!Schema::hasColumn('properties', 'nearby_hospitals')) {
                $table->string('nearby_hospitals')->nullable()->after('nearby_schools');
            }
            if (!Schema::hasColumn('properties', 'nearby_malls')) {
                $table->string('nearby_malls')->nullable()->after('nearby_hospitals');
            }
            if (!Schema::hasColumn('properties', 'nearby_metro')) {
                $table->string('nearby_metro')->nullable()->after('nearby_malls');
            }
            if (!Schema::hasColumn('properties', 'nearby_bus_stand')) {
                $table->string('nearby_bus_stand')->nullable()->after('nearby_metro');
            }
            if (!Schema::hasColumn('properties', 'distance_metrics')) {
                $table->text('distance_metrics')->nullable()->after('nearby_bus_stand');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'nearby_schools')) {
                $table->dropColumn('nearby_schools');
            }
            if (Schema::hasColumn('properties', 'nearby_hospitals')) {
                $table->dropColumn('nearby_hospitals');
            }
            if (Schema::hasColumn('properties', 'nearby_malls')) {
                $table->dropColumn('nearby_malls');
            }
            if (Schema::hasColumn('properties', 'nearby_metro')) {
                $table->dropColumn('nearby_metro');
            }
            if (Schema::hasColumn('properties', 'nearby_bus_stand')) {
                $table->dropColumn('nearby_bus_stand');
            }
            if (Schema::hasColumn('properties', 'distance_metrics')) {
                $table->dropColumn('distance_metrics');
            }
        });
    }
};
