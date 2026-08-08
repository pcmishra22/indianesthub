<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auctions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();

            // Seller — whoever submitted the property for auction. Kept as a
            // polymorphic-lite pair (only one of these will be set) rather than
            // a morph column, so existing dealer/builder/user relations can be
            // reused directly.
            $table->unsignedBigInteger('seller_user_id')->nullable();
            $table->unsignedBigInteger('seller_dealer_id')->nullable();

            // ─────────────────────────────────────────────
            // Lifecycle
            //   submitted          seller has filled the form, docs uploading
            //   under_review       all docs uploaded, waiting on admin
            //   changes_requested  admin asked for corrected/additional docs
            //   approved           docs verified, auction scheduled but not live
            //   live               accepting bids
            //   ended_sold         reserve met, auction closed with a winner
            //   ended_unsold       time ran out, no valid winner, seller declined to extend further
            //   cancelled          withdrawn by seller or admin
            // ─────────────────────────────────────────────
            $table->string('status')->default('submitted')->index();

            // Pricing
            $table->decimal('reserve_price', 14, 2);       // minimum acceptable price, set by seller
            $table->decimal('starting_bid', 14, 2);         // opening bid amount, shown publicly
            $table->decimal('bid_increment', 12, 2)->default(10000); // minimum step between bids
            $table->decimal('current_highest_bid', 14, 2)->nullable();
            $table->unsignedBigInteger('current_highest_bidder_id')->nullable(); // users.id

            // Schedule
            $table->timestamp('scheduled_start_at')->nullable();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('original_end_at')->nullable(); // preserved even after extensions, for display ("originally ended at...")
            $table->unsignedInteger('extension_count')->default(0);
            $table->unsignedInteger('max_extensions')->default(3); // reserve-not-met auto-extends; after this many, ends unsold instead

            // Admin review
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();

            // Reason the seller gave for the urgent sale — useful context for
            // admins reviewing the listing, and builds buyer trust/transparency.
            $table->string('sale_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'end_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
