<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('property_management_leads', function (Blueprint $table) {
            $table->id();

            // Context — which property/project triggered this lead
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('builder_project_id')->nullable();

            // Applicant / owner
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();

            // Property management details
            $table->string('property_type')->nullable();       // apartment / villa / commercial
            $table->string('service_type')->default('full-management'); // tenant-management / rent-collection / maintenance / full-management
            $table->string('city')->nullable();
            $table->unsignedInteger('num_properties')->nullable(); // owns how many units needing management
            $table->boolean('currently_rented')->default(false);

            // Tracking
            $table->string('source')->default('website');
            $table->string('source_page')->nullable();
            $table->string('status')->default('new');          // new / contacted / site-visit / onboarded / lost
            $table->text('notes')->nullable();

            // Metadata
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('property_management_leads');
    }
};
