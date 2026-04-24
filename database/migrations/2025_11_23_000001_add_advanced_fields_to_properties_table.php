<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('bhk_type')->nullable()->after('property_type');
            $table->string('option_type')->nullable()->after('bhk_type'); // Rent/Sell/PG/Co-living
            $table->string('listing_type')->default('Owner')->after('option_type'); // Owner/Broker/Builder
            $table->integer('parking')->default(0)->after('bathrooms');
            $table->boolean('pet_friendly')->default(false)->after('parking');
            $table->string('video_url')->nullable()->after('floor_plan');
            $table->string('virtual_tour_url')->nullable()->after('video_url');
            $table->boolean('is_featured')->default(false)->after('status');
            $table->boolean('is_premium')->default(false)->after('is_featured');
            $table->integer('views_count')->default(0)->after('is_premium');
        });
    }

    public function down() {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'bhk_type', 'option_type', 'listing_type', 'parking', 
                'pet_friendly', 'video_url', 'virtual_tour_url', 
                'is_featured', 'is_premium', 'views_count'
            ]);
        });
    }
};
