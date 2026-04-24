<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->after('dealer_id')->constrained()->onDelete('set null');
            $table->string('payment_type')->default('property_listing')->after('plan_type');
            $table->integer('listing_duration_days')->default(30)->after('amount');
            $table->date('listing_start_date')->nullable()->after('listing_duration_days');
            $table->date('listing_end_date')->nullable()->after('listing_start_date');
            $table->string('card_last_four')->nullable()->after('payment_method');
            $table->string('card_brand')->nullable()->after('card_last_four');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
            $table->dropColumn(['property_id', 'payment_type', 'listing_duration_days', 'listing_start_date', 'listing_end_date', 'card_last_four', 'card_brand']);
        });
    }
};
