<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'bedrooms')) {
                $table->integer('bedrooms')->nullable();
            }
            if (!Schema::hasColumn('properties', 'bathrooms')) {
                $table->integer('bathrooms')->nullable();
            }
            if (!Schema::hasColumn('properties', 'balconies')) {
                $table->integer('balconies')->nullable();
            }
            if (!Schema::hasColumn('properties', 'total_floors')) {
                $table->integer('total_floors')->nullable();
            }
            if (!Schema::hasColumn('properties', 'floor_number')) {
                $table->integer('floor_number')->nullable();
            }
            if (!Schema::hasColumn('properties', 'facing')) {
                $table->string('facing', 20)->nullable();
            }
            if (!Schema::hasColumn('properties', 'property_age')) {
                $table->string('property_age', 50)->nullable();
            }
            if (!Schema::hasColumn('properties', 'furnishing_status')) {
                $table->string('furnishing_status', 20)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'bedrooms')) {
                $table->dropColumn('bedrooms');
            }
            if (Schema::hasColumn('properties', 'bathrooms')) {
                $table->dropColumn('bathrooms');
            }
            if (Schema::hasColumn('properties', 'balconies')) {
                $table->dropColumn('balconies');
            }
            if (Schema::hasColumn('properties', 'total_floors')) {
                $table->dropColumn('total_floors');
            }
            if (Schema::hasColumn('properties', 'floor_number')) {
                $table->dropColumn('floor_number');
            }
            if (Schema::hasColumn('properties', 'facing')) {
                $table->dropColumn('facing');
            }
            if (Schema::hasColumn('properties', 'property_age')) {
                $table->dropColumn('property_age');
            }
            if (Schema::hasColumn('properties', 'furnishing_status')) {
                $table->dropColumn('furnishing_status');
            }
        });
    }
};
