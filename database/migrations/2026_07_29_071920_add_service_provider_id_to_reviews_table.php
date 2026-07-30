<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'service_provider_id')) {
                $table->foreignId('service_provider_id')->nullable()->after('agent_id')
                    ->constrained('service_providers')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'service_provider_id')) {
                $table->dropConstrainedForeignId('service_provider_id');
            }
        });
    }
};
