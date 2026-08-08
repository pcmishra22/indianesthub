<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Regular site users (bidders) paying an auction deposit —
            // separate from dealer_id, which is used for listing-payment flows.
            $table->foreignId('user_id')->nullable()->after('dealer_id')->constrained('users')->nullOnDelete();
            $table->foreignId('auction_id')->nullable()->after('property_id')->constrained('auctions')->nullOnDelete();

            // 'refunded' / 'forfeited' on top of the existing pending/verified/failed
            // states already used by payment_data status handling for auction deposits.
            $table->timestamp('refunded_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['auction_id']);
            $table->dropColumn(['user_id', 'auction_id', 'refunded_at']);
        });
    }
};
