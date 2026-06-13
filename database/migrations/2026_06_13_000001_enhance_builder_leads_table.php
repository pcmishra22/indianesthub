<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            $table->json('call_log')->nullable()->after('user_agent'); // [{at, note, duration}]
        });
    }

    public function down(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            $table->dropColumn(['call_log']);
        });
    }
};
