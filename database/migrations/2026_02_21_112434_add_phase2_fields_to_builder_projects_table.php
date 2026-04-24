<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builder_projects', function (Blueprint $table) {
            $table->integer('total_towers')->nullable()->after('total_units');
            $table->string('floors_per_tower')->nullable()->after('total_towers');
            $table->string('master_plan')->nullable()->after('cover_image');
            $table->string('brochure')->nullable()->after('master_plan');
            $table->string('video_url')->nullable()->after('brochure');
            $table->string('virtual_tour_url')->nullable()->after('video_url');
            $table->decimal('latitude', 10, 7)->nullable()->after('state');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('nearby_schools')->nullable()->after('longitude');
            $table->string('nearby_hospitals')->nullable()->after('nearby_schools');
            $table->string('metro_distance')->nullable()->after('nearby_hospitals');
            $table->string('connectivity_score')->nullable()->after('metro_distance');
            $table->string('future_infra')->nullable()->after('connectivity_score');
            $table->integer('views_count')->default(0)->after('is_featured');
            $table->integer('leads_count')->default(0)->after('views_count');
        });
    }

    public function down(): void
    {
        Schema::table('builder_projects', function (Blueprint $table) {
            $table->dropColumn([
                'total_towers', 'floors_per_tower',
                'master_plan', 'brochure', 'video_url', 'virtual_tour_url',
                'latitude', 'longitude',
                'nearby_schools', 'nearby_hospitals', 'metro_distance', 'connectivity_score', 'future_infra',
                'views_count', 'leads_count',
            ]);
        });
    }
};
