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
            if (!Schema::hasColumn('properties', 'views_count')) {
                $table->unsignedBigInteger('views_count')->default(0)->after('priority_score');
            }
            if (!Schema::hasColumn('properties', 'shortlist_count')) {
                $table->unsignedBigInteger('shortlist_count')->default(0)->after('views_count');
            }
            if (!Schema::hasColumn('properties', 'inquiries_count')) {
                $table->unsignedBigInteger('inquiries_count')->default(0)->after('shortlist_count');
            }
            if (!Schema::hasColumn('properties', 'last_viewed_at')) {
                $table->timestamp('last_viewed_at')->nullable()->after('inquiries_count');
            }
            if (!Schema::hasColumn('properties', 'expiry_date')) {
                $table->date('expiry_date')->nullable()->after('last_viewed_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'views_count')) {
                $table->dropColumn('views_count');
            }
            if (Schema::hasColumn('properties', 'shortlist_count')) {
                $table->dropColumn('shortlist_count');
            }
            if (Schema::hasColumn('properties', 'inquiries_count')) {
                $table->dropColumn('inquiries_count');
            }
            if (Schema::hasColumn('properties', 'last_viewed_at')) {
                $table->dropColumn('last_viewed_at');
            }
            if (Schema::hasColumn('properties', 'expiry_date')) {
                $table->dropColumn('expiry_date');
            }
        });
    }
};
