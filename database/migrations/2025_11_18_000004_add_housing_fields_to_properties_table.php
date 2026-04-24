<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('rera_id')->nullable();
            $table->string('possession_date')->nullable();
            $table->string('price_range')->nullable();
            $table->string('floor_plan')->nullable();
        });
    }
    public function down() {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['rera_id', 'possession_date', 'price_range', 'floor_plan']);
        });
    }
};
