<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('builder_leads', 'external_lead_id')) {
                $table->string('external_lead_id')->nullable()->unique()->after('source');
            }
        });

        Schema::table('builder_leads', function (Blueprint $table) {
            $table->string('phone')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            $table->dropColumn(['external_lead_id']);
        });

        Schema::table('builder_leads', function (Blueprint $table) {
            $table->string('phone')->nullable(false)->change();
        });
    }
};
