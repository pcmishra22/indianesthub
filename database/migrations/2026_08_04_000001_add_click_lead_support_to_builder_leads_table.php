<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            // Links a lead back to the specific property listing that was
            // clicked, so a call/WhatsApp click can be traced to an exact
            // unit — not just "somewhere in this builder's project."
            if (!Schema::hasColumn('builder_leads', 'property_id')) {
                $table->foreignId('property_id')->nullable()->after('builder_project_id')
                    ->constrained('properties')->nullOnDelete();
            }

            // Defensive: these columns are already referenced in the
            // BuilderLead model's $fillable, and enhance_builder_leads_table
            // (2026_06_13) even places call_log ->after('user_agent'), which
            // means user_agent already existed on the live DB by then — but
            // no migration in this repo actually creates ip_address,
            // user_agent, notes, follow_up_at, or hot_score. They were
            // evidently added directly on the server outside of migrations
            // at some point. Re-declaring them here with hasColumn guards
            // keeps `php artisan migrate` idempotent and safe to run on any
            // environment (fresh install, staging, or the existing prod DB)
            // without erroring on "column already exists" or drifting away
            // from what the live schema actually has.
            if (!Schema::hasColumn('builder_leads', 'ip_address')) {
                $table->string('ip_address')->nullable();
            }
            if (!Schema::hasColumn('builder_leads', 'user_agent')) {
                $table->string('user_agent')->nullable();
            }
            if (!Schema::hasColumn('builder_leads', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('builder_leads', 'follow_up_at')) {
                $table->timestamp('follow_up_at')->nullable();
            }
            if (!Schema::hasColumn('builder_leads', 'hot_score')) {
                $table->unsignedTinyInteger('hot_score')->default(0);
            }
        });

        // name/phone were required (NOT NULL) at table creation, which made
        // sense when every lead came from a filled-in form. Click-tracked
        // leads (call_click/whatsapp_click) are created the instant someone
        // taps the button — before staff has spoken to them and captured
        // who they are — so these need to be fillable-in-later instead of
        // required-up-front.
        Schema::table('builder_leads', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('phone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            if (Schema::hasColumn('builder_leads', 'property_id')) {
                $table->dropForeign(['property_id']);
                $table->dropColumn('property_id');
            }
        });

        Schema::table('builder_leads', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
        });
    }
};
