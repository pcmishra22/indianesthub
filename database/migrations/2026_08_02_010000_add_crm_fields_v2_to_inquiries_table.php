<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            if (!Schema::hasColumn('inquiries', 'lead_type')) {
                $table->string('lead_type')->default('general')->after('message'); // general / visit / callback / brochure / whatsapp / facebook_lead
            }
            if (!Schema::hasColumn('inquiries', 'hot_score')) {
                $table->unsignedTinyInteger('hot_score')->default(0)->after('assigned_agent_id');
            }
            if (!Schema::hasColumn('inquiries', 'call_log')) {
                $table->json('call_log')->nullable()->after('hot_score'); // [{at, note, duration}]
            }
            if (!Schema::hasColumn('inquiries', 'follow_up_at')) {
                $table->timestamp('follow_up_at')->nullable()->after('call_log');
            }
            // Idempotency key for leads pulled from external sources (e.g. Facebook leadgen_id)
            // so the same social lead never gets inserted twice.
            if (!Schema::hasColumn('inquiries', 'external_lead_id')) {
                $table->string('external_lead_id')->nullable()->unique()->after('source');
            }
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['lead_type', 'hot_score', 'call_log', 'follow_up_at', 'external_lead_id']);
        });
    }
};
