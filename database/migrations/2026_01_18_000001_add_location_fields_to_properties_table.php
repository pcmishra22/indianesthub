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
            if (!Schema::hasColumn('properties', 'country')) {
                $table->string('country')->default('India');
            }
            if (!Schema::hasColumn('properties', 'locality')) {
                $table->string('locality')->nullable();
            }
            if (!Schema::hasColumn('properties', 'sub_locality')) {
                $table->string('sub_locality')->nullable();
            }
            if (!Schema::hasColumn('properties', 'society_name')) {
                $table->string('society_name')->nullable();
            }
            if (!Schema::hasColumn('properties', 'landmark')) {
                $table->string('landmark')->nullable();
            }
            if (!Schema::hasColumn('properties', 'pincode')) {
                $table->string('pincode')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('properties', 'country')) $columns[] = 'country';
            if (Schema::hasColumn('properties', 'locality')) $columns[] = 'locality';
            if (Schema::hasColumn('properties', 'sub_locality')) $columns[] = 'sub_locality';
            if (Schema::hasColumn('properties', 'society_name')) $columns[] = 'society_name';
            if (Schema::hasColumn('properties', 'landmark')) $columns[] = 'landmark';
            if (Schema::hasColumn('properties', 'pincode')) $columns[] = 'pincode';
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
