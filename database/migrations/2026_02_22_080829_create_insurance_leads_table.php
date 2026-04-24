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
        Schema::create('insurance_leads', function (Blueprint $table) {
            $table->id();

            // Context — which property/project triggered the lead
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('builder_project_id')->nullable();
            $table->unsignedBigInteger('loan_lead_id')->nullable(); // bundled with loan

            // Applicant info
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();

            // Property details for quote
            $table->decimal('property_value', 15, 2)->nullable();
            $table->string('property_type')->nullable();       // Flat / Villa / Plot / Row House
            $table->string('property_city')->nullable();
            $table->string('possession_status')->default('ready'); // ready / under-construction

            // Insurance preferences
            $table->string('insurance_type')->default('home'); // home / content / both / fire
            $table->decimal('coverage_amount', 15, 2)->nullable();
            $table->string('preferred_insurer')->nullable();   // HDFC ERGO / Bajaj / Tata AIG / etc.

            // Revenue tracking
            $table->string('source')->default('website');
            // website / property-page / project-page / loan-bundle / post-visit / possession
            $table->string('source_page')->nullable();
            $table->string('status')->default('new');
            // new / contacted / quoted / converted / lost
            $table->decimal('premium_quoted', 10, 2)->nullable();   // annual premium in ₹
            $table->decimal('commission_earned', 10, 2)->nullable(); // ₹ earned on conversion
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('phone');
            $table->index('loan_lead_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insurance_leads');
    }
};
