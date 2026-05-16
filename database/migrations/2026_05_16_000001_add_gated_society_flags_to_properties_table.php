<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // The app currently inserts/uses these columns (see Property model + controllers).
            // Some environments may be missing them due to partial migrations.

            if (!Schema::hasColumn('properties', 'gated_society')) {
                $table->boolean('gated_society')->nullable()->after('expiry_date');
            }

            if (!Schema::hasColumn('properties', 'corner_property')) {
                $table->boolean('corner_property')->nullable()->after('gated_society');
            }

            if (!Schema::hasColumn('properties', 'vastu_compliant')) {
                $table->boolean('vastu_compliant')->nullable()->after('corner_property');
            }

            if (!Schema::hasColumn('properties', 'wheelchair_friendly')) {
                $table->boolean('wheelchair_friendly')->nullable()->after('vastu_compliant');
            }

            if (!Schema::hasColumn('properties', 'overlooking_park')) {
                $table->boolean('overlooking_park')->nullable()->after('wheelchair_friendly');
            }

            if (!Schema::hasColumn('properties', 'overlooking_road')) {
                $table->boolean('overlooking_road')->nullable()->after('overlooking_park');
            }

            if (!Schema::hasColumn('properties', 'income_property')) {
                $table->boolean('income_property')->nullable()->after('overlooking_road');
            }

            if (!Schema::hasColumn('properties', 'distress_sale')) {
                $table->boolean('distress_sale')->nullable()->after('income_property');
            }

            // Note: pet_friendly is already added by other migrations, but keep consistent.
            if (!Schema::hasColumn('properties', 'pet_friendly')) {
                $table->boolean('pet_friendly')->nullable()->after('distress_sale');
            }
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $columns = [
                'gated_society',
                'corner_property',
                'vastu_compliant',
                'wheelchair_friendly',
                'overlooking_park',
                'overlooking_road',
                'income_property',
                'distress_sale',
                'pet_friendly',
            ];

            // Drop only if present to make down safe.
            $drop = [];
            foreach ($columns as $col) {
                if (Schema::hasColumn('properties', $col)) {
                    $drop[] = $col;
                }
            }

            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};

