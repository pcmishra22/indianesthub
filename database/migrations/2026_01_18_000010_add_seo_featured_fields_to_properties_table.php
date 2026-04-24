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
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'slug')) {
                $table->string('slug')->nullable()->after('distance_metrics');
            }
            if (!Schema::hasColumn('properties', 'meta_title')) {
                $table->string('meta_title')->nullable()->after('slug');
            }
            if (!Schema::hasColumn('properties', 'meta_description')) {
                $table->text('meta_description')->nullable()->after('meta_title');
            }
            if (!Schema::hasColumn('properties', 'search_tags')) {
                $table->string('search_tags')->nullable()->after('meta_description');
            }
            if (!Schema::hasColumn('properties', 'featured')) {
                $table->boolean('featured')->default(false)->after('search_tags');
            }
            if (!Schema::hasColumn('properties', 'priority_score')) {
                $table->integer('priority_score')->nullable()->after('featured');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('properties', 'meta_title')) {
                $table->dropColumn('meta_title');
            }
            if (Schema::hasColumn('properties', 'meta_description')) {
                $table->dropColumn('meta_description');
            }
            if (Schema::hasColumn('properties', 'search_tags')) {
                $table->dropColumn('search_tags');
            }
            if (Schema::hasColumn('properties', 'featured')) {
                $table->dropColumn('featured');
            }
            if (Schema::hasColumn('properties', 'priority_score')) {
                $table->dropColumn('priority_score');
            }
        });
    }
};
