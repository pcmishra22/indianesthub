<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * When admin "disables" a property we set status = inactive but need
     * to remember what it was before (active/sold/rented/etc.) so "enable"
     * can restore it correctly instead of always going back to "active".
     */
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'previous_status')) {
                $table->string('previous_status', 30)->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'previous_status')) {
                $table->dropColumn('previous_status');
            }
        });
    }
};
