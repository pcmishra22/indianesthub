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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('property_dealer_id')->constrained()->onDelete('cascade');
            $table->string('plan'); // 'basic', 'premium', 'enterprise'
            $table->decimal('price', 10, 2);
            $table->integer('property_limit'); // Max properties allowed
            $table->integer('featured_limit'); // Max featured properties
            $table->boolean('priority_support')->default(false);
            $table->boolean('analytics_access')->default(false);
            $table->date('renewal_date')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');
            $table->timestamps();
            
            $table->index(['property_dealer_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
