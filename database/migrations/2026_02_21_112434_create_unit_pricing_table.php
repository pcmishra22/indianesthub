<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_pricing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();
            $table->decimal('base_price', 15, 2)->nullable();
            $table->decimal('current_price', 15, 2)->nullable();
            $table->json('price_history')->nullable();   // [{price, date, note}]
            $table->text('offer')->nullable();           // e.g. "No stamp duty + Free parking"
            $table->decimal('emi_amount', 12, 2)->nullable();
            $table->decimal('maintenance_charges', 10, 2)->nullable();
            $table->string('payment_plan')->nullable();  // e.g. "10:80:10"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_pricing');
    }
};
