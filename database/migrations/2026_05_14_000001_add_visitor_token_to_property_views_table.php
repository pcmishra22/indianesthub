<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            if (!Schema::hasColumn('property_views', 'visitor_token')) {
                $table->string('visitor_token', 64)->nullable()->after('session_id');
                $table->index(['visitor_token', 'property_id']);
            }
        });

        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'visitor_token')) {
                $table->string('visitor_token', 64)->nullable()->after('ip_address');
                $table->index(['visitor_token', 'property_id']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            $table->dropColumn(['visitor_token']);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['visitor_token']);
        });
    }
};

