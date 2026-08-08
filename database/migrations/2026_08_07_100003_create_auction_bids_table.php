<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auction_bids', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('auctions')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('amount', 14, 2);
            $table->boolean('is_winning')->default(false); // true only for the single current-highest bid row
            $table->boolean('is_auto_bid')->default(false); // reserved for a future max-bid/proxy-bidding feature

            $table->string('ip_address', 45)->nullable();

            $table->timestamps();

            $table->index(['auction_id', 'amount']);
            $table->index(['auction_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_bids');
    }
};
