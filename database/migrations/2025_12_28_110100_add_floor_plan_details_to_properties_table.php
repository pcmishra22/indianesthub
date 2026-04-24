<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'floor_plan_details')) {
                $table->text('floor_plan_details')->nullable()->after('floor_plan');
            }
        });
    }
    public function down() {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'floor_plan_details')) {
                $table->dropColumn('floor_plan_details');
            }
        });
    }
};
