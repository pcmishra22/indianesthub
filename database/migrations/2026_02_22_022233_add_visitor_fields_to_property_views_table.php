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
        Schema::table('property_views', function (Blueprint $table) {
            $table->string('session_id')->nullable()->after('user_id');
            $table->string('ip_address', 45)->nullable()->after('session_id');
            $table->string('device')->nullable()->after('ip_address');      // mobile / tablet / desktop
            $table->string('browser')->nullable()->after('device');
            $table->string('referrer')->nullable()->after('browser');
            $table->string('page_url')->nullable()->after('referrer');
        });

        if (!Schema::hasColumn('builder_leads', 'ip_address')) {
            Schema::table('builder_leads', function (Blueprint $table) {
                $table->string('ip_address', 45)->nullable()->after('source');
                $table->string('user_agent')->nullable()->after('ip_address');
            });
        }

        if (!Schema::hasColumn('inquiries', 'ip_address')) {
            Schema::table('inquiries', function (Blueprint $table) {
                $table->string('ip_address', 45)->nullable()->after('assigned_agent_id');
                $table->string('user_agent')->nullable()->after('ip_address');
                $table->string('source')->default('website')->after('user_agent');
            });
        }
    }

    public function down(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            $table->dropColumn(['session_id','ip_address','device','browser','referrer','page_url']);
        });
    }
};
