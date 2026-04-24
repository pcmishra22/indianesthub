<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'covered_parking')) {
                $table->integer('covered_parking')->nullable();
            }
            if (!Schema::hasColumn('properties', 'open_parking')) {
                $table->integer('open_parking')->nullable();
            }
            if (!Schema::hasColumn('properties', 'water_supply')) {
                $table->string('water_supply', 30)->nullable();
            }
            if (!Schema::hasColumn('properties', 'electricity_status')) {
                $table->string('electricity_status', 50)->nullable();
            }
            if (!Schema::hasColumn('properties', 'gas_pipeline')) {
                $table->boolean('gas_pipeline')->nullable();
            }
            if (!Schema::hasColumn('properties', 'drainage')) {
                $table->boolean('drainage')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'covered_parking')) {
                $table->dropColumn('covered_parking');
            }
            if (Schema::hasColumn('properties', 'open_parking')) {
                $table->dropColumn('open_parking');
            }
            if (Schema::hasColumn('properties', 'water_supply')) {
                $table->dropColumn('water_supply');
            }
            if (Schema::hasColumn('properties', 'electricity_status')) {
                $table->dropColumn('electricity_status');
            }
            if (Schema::hasColumn('properties', 'gas_pipeline')) {
                $table->dropColumn('gas_pipeline');
            }
            if (Schema::hasColumn('properties', 'drainage')) {
                $table->dropColumn('drainage');
            }
        });
    }
};
