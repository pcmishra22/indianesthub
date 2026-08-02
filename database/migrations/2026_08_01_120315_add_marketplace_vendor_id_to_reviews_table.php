<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'marketplace_vendor_id')) {
                $table->foreignId('marketplace_vendor_id')->nullable()->after('service_provider_id')
                    ->constrained('marketplace_vendors')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'marketplace_vendor_id')) {
                $table->dropConstrainedForeignId('marketplace_vendor_id');
            }
        });
    }
};
