<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_trackings', function (Blueprint $table) {
            $table->unsignedBigInteger('property_id')->nullable()->after('recipient_type');
            $table->string('sender_type')->nullable()->after('property_id'); // 'dealer' | 'builder'
            $table->unsignedBigInteger('sender_id')->nullable()->after('sender_type');

            $table->index('property_id');
            $table->index(['sender_type', 'sender_id']);
        });
    }

    public function down(): void
    {
        Schema::table('email_trackings', function (Blueprint $table) {
            $table->dropIndex(['property_id']);
            $table->dropIndex(['sender_type', 'sender_id']);
            $table->dropColumn(['property_id', 'sender_type', 'sender_id']);
        });
    }
};
