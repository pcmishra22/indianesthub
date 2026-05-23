<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // placements / targeting
            $table->string('mobile_image')->nullable();
            $table->string('target_url')->nullable();
            $table->string('placement')->nullable();

            // activation window
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            // ranking + reporting
            $table->integer('priority')->default(1);
            $table->integer('impressions')->default(0);
            $table->integer('clicks')->default(0);

            // legacy `status` is boolean; this extra flag makes it easier for UI.
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            //
        });
    }
};
