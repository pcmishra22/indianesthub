<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Extends property_views beyond plain "page view" so we can log what a
     * visitor DID on the page too: page_view, call_click, whatsapp_click.
     * This gives admin maximum visibility per visitor without adding a
     * whole new tracking table.
     */
    public function up(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            if (!Schema::hasColumn('property_views', 'event_type')) {
                $table->string('event_type', 30)->default('page_view')->after('property_id');
                $table->index(['property_id', 'event_type']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            if (Schema::hasColumn('property_views', 'event_type')) {
                $table->dropIndex(['property_id', 'event_type']);
                $table->dropColumn('event_type');
            }
        });
    }
};
