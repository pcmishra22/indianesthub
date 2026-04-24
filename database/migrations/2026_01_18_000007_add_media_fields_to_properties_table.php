<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'cover_image')) {
                $table->string('cover_image', 255)->nullable();
            }
            if (!Schema::hasColumn('properties', 'gallery_images')) {
                $table->json('gallery_images')->nullable();
            }
            if (!Schema::hasColumn('properties', 'floor_plan_images')) {
                $table->json('floor_plan_images')->nullable();
            }
            if (!Schema::hasColumn('properties', 'video_url')) {
                $table->string('video_url', 255)->nullable();
            }
            if (!Schema::hasColumn('properties', 'virtual_tour_url')) {
                $table->string('virtual_tour_url', 255)->nullable();
            }
            if (!Schema::hasColumn('properties', 'brochure_pdf')) {
                $table->string('brochure_pdf', 255)->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
            if (Schema::hasColumn('properties', 'gallery_images')) {
                $table->dropColumn('gallery_images');
            }
            if (Schema::hasColumn('properties', 'floor_plan_images')) {
                $table->dropColumn('floor_plan_images');
            }
            if (Schema::hasColumn('properties', 'video_url')) {
                $table->dropColumn('video_url');
            }
            if (Schema::hasColumn('properties', 'virtual_tour_url')) {
                $table->dropColumn('virtual_tour_url');
            }
            if (Schema::hasColumn('properties', 'brochure_pdf')) {
                $table->dropColumn('brochure_pdf');
            }
        });
    }
};
