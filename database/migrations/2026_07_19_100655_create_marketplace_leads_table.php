<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Home Marketplace — leads
 *
 * A lead is one user request for a vendor, anchored to a property.
 * The user fills BHK / window count / fabric / etc. and the lead is
 * routed to ONE vendor (the one whose card they clicked).
 *
 * Status flow:
 *   new → contacted → won | lost
 *   won → commission_collected
 *
 * `order_value` and `commission_amount` are filled when the admin marks
 * the lead as won. We keep them nullable so a "lost" lead isn't a data hole.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')
                ->nullable()
                ->constrained('properties')
                ->nullOnDelete();
            $table->foreignId('vendor_id')
                ->constrained('marketplace_vendors')
                ->restrictOnDelete();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('marketplace_products')
                ->nullOnDelete();

            // User-provided
            $table->string('name', 120);
            $table->string('email', 160)->nullable();
            $table->string('phone', 20);
            $table->string('city', 80)->nullable();
            $table->string('bhk_type', 30)->nullable();
            $table->unsignedTinyInteger('window_count')->nullable();
            $table->string('fabric_preference', 120)->nullable();
            $table->text('notes')->nullable();

            // Tracking
            $table->string('source_page', 255)->nullable();
            $table->string('ip_address', 64)->nullable();
            $table->string('user_agent', 512)->nullable();
            $table->string('visitor_token', 64)->nullable();

            // Lifecycle
            $table->string('status', 20)->default('new'); // new|contacted|won|lost
            $table->decimal('order_value', 12, 2)->nullable();
            $table->decimal('commission_amount', 12, 2)->nullable();
            $table->boolean('commission_collected')->default(false);
            $table->timestamp('contacted_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index(['vendor_id', 'status']);
            $table->index('property_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_leads');
    }
};
