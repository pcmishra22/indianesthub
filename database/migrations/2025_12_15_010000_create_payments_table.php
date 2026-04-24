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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dealer_id')->nullable();
            $table->string('plan_type')->nullable();
            $table->string('plan_name')->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->text('payment_data')->nullable();
            $table->timestamps();
            $table->index(['dealer_id', 'status']);
            $table->index('transaction_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
