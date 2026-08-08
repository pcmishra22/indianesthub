<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Seller sets this explicitly (or admin adjusts on approval) instead
            // of the platform silently computing it as a % of reserve.
            $table->decimal('emd_amount', 12, 2)->nullable()->after('bid_increment');

            // Reason for selling is never forced public — only shown on the
            // auction page if the seller explicitly opts in.
            $table->boolean('sale_reason_public')->default(false)->after('sale_reason');

            // Seller's preferred duration at submission time (days). Admin can
            // still set the actual start/end when approving.
            $table->unsignedInteger('duration_days_requested')->nullable()->after('sale_reason_public');

            // ── 5-level verification checklist (Level 1–2 are computed live
            // from KYC/documents; 3–5 are explicit manual admin sign-offs) ──
            $table->timestamp('property_tax_verified_at')->nullable();
            $table->timestamp('site_verified_at')->nullable();
            $table->timestamp('legal_due_diligence_at')->nullable();
            $table->text('legal_due_diligence_notes')->nullable();

            // ── Post-auction seller decision ──
            // null | accepted | negotiating | rejected | reauction_requested
            $table->string('seller_decision')->nullable();
            $table->timestamp('seller_decision_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn([
                'emd_amount', 'sale_reason_public', 'duration_days_requested',
                'property_tax_verified_at', 'site_verified_at',
                'legal_due_diligence_at', 'legal_due_diligence_notes',
                'seller_decision', 'seller_decision_at',
            ]);
        });
    }
};
