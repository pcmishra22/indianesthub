<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('marketplace_vendors', function (Blueprint $table) {
            if (!Schema::hasColumn('marketplace_vendors', 'latitude')) {
                $table->decimal('latitude', 10, 7)->nullable()->after('address');
            }
            if (!Schema::hasColumn('marketplace_vendors', 'longitude')) {
                $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            }
            if (!Schema::hasColumn('marketplace_vendors', 'gst_number')) {
                $table->string('gst_number')->nullable()->after('commission_pct');
            }
        });
    }

    public function down(): void
    {
        Schema::table('marketplace_vendors', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'gst_number']);
        });
    }
};
