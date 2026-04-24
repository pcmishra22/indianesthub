<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'ownership_type')) {
                $table->string('ownership_type', 20)->nullable();
            }
            if (!Schema::hasColumn('properties', 'property_approval')) {
                $table->string('property_approval', 100)->nullable();
            }
            if (!Schema::hasColumn('properties', 'rera_id')) {
                $table->string('rera_id', 100)->nullable();
            }
            if (!Schema::hasColumn('properties', 'rera_verified')) {
                $table->boolean('rera_verified')->nullable();
            }
            if (!Schema::hasColumn('properties', 'occupancy_certificate')) {
                $table->string('occupancy_certificate', 100)->nullable();
            }
            if (!Schema::hasColumn('properties', 'completion_certificate')) {
                $table->string('completion_certificate', 100)->nullable();
            }
            if (!Schema::hasColumn('properties', 'legal_clearance_status')) {
                $table->string('legal_clearance_status', 100)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'ownership_type')) {
                $table->dropColumn('ownership_type');
            }
            if (Schema::hasColumn('properties', 'property_approval')) {
                $table->dropColumn('property_approval');
            }
            if (Schema::hasColumn('properties', 'rera_id')) {
                $table->dropColumn('rera_id');
            }
            if (Schema::hasColumn('properties', 'rera_verified')) {
                $table->dropColumn('rera_verified');
            }
            if (Schema::hasColumn('properties', 'occupancy_certificate')) {
                $table->dropColumn('occupancy_certificate');
            }
            if (Schema::hasColumn('properties', 'completion_certificate')) {
                $table->dropColumn('completion_certificate');
            }
            if (Schema::hasColumn('properties', 'legal_clearance_status')) {
                $table->dropColumn('legal_clearance_status');
            }
        });
    }
};
