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
            // Make property_dealer_id nullable so builder properties don't need a dealer
            $table->unsignedBigInteger('property_dealer_id')->nullable()->change();
            // Add builder foreign keys
            $table->foreignId('builder_id')->nullable()->after('property_dealer_id')
                  ->constrained('builders')->nullOnDelete();
            $table->foreignId('builder_project_id')->nullable()->after('builder_id')
                  ->constrained('builder_projects')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['builder_id']);
            $table->dropForeign(['builder_project_id']);
            $table->dropColumn(['builder_id', 'builder_project_id']);
            $table->unsignedBigInteger('property_dealer_id')->nullable(false)->change();
        });
    }
};
