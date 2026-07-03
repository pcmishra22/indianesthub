<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * `status` on builder_projects already tracks construction progress
     * (Upcoming / Under Construction / Ready to Move / Completed), so we
     * add a separate `is_active` flag purely for admin enable/disable
     * (hide from public site without touching construction status).
     */
    public function up(): void
    {
        Schema::table('builder_projects', function (Blueprint $table) {
            if (!Schema::hasColumn('builder_projects', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
                $table->index('is_active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('builder_projects', function (Blueprint $table) {
            if (Schema::hasColumn('builder_projects', 'is_active')) {
                $table->dropIndex(['is_active']);
                $table->dropColumn('is_active');
            }
        });
    }
};
