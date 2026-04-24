<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            // $table->boolean('gated_society')->nullable()->after('expiry_date'); // Already exists
            // $table->boolean('corner_property')->nullable()->after('gated_society'); // Already exists
            // $table->boolean('vastu_compliant')->nullable()->after('corner_property'); // Already exists
            // $table->boolean('pet_friendly')->nullable()->after('vastu_compliant'); // Already exists
            // $table->boolean('wheelchair_friendly')->nullable()->after('vastu_compliant'); // Already exists
            // $table->boolean('overlooking_park')->nullable()->after('wheelchair_friendly'); // Already exists
            // $table->boolean('overlooking_road')->nullable()->after('overlooking_park'); // Already exists
            // $table->boolean('income_property')->nullable()->after('overlooking_road'); // Already exists
            // $table->boolean('distress_sale')->nullable()->after('income_property'); // Already exists
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            // $table->dropColumn([
            //     'gated_society',
            //     'corner_property',
            //     'vastu_compliant',
            //     'pet_friendly',
            //     'wheelchair_friendly',
            //     'overlooking_park',
            //     'overlooking_road',
            //     'income_property',
            //     'distress_sale',
            // ]);
        });
    }
};
