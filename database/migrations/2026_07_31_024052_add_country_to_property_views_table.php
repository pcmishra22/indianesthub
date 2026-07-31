<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            if (!Schema::hasColumn('property_views', 'country')) {
                $table->string('country')->nullable()->after('ip_address');
            }
            if (!Schema::hasColumn('property_views', 'country_code')) {
                $table->string('country_code', 2)->nullable()->after('country');
            }
        });
    }

    public function down(): void
    {
        Schema::table('property_views', function (Blueprint $table) {
            $table->dropColumn(['country', 'country_code']);
        });
    }
};
