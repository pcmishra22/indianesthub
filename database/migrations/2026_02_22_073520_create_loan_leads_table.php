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
        Schema::create('loan_leads', function (Blueprint $table) {
            $table->id();

            // Context — which property/project triggered this lead
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('builder_project_id')->nullable();

            // Applicant
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();

            // Loan details
            $table->decimal('loan_amount', 15, 2)->nullable();       // desired loan amount
            $table->decimal('property_value', 15, 2)->nullable();    // total property price
            $table->string('employment_type')->nullable();            // salaried / self-employed / business
            $table->decimal('monthly_income', 15, 2)->nullable();
            $table->string('loan_tenure')->nullable();                // 5 / 10 / 15 / 20 / 25 / 30
            $table->string('loan_purpose')->default('purchase');      // purchase / construction / renovation / balance-transfer

            // Tracking
            $table->string('source')->default('website');             // property-page / project-page / schedule-form / direct
            $table->string('source_page')->nullable();                // URL/slug of origin page
            $table->string('status')->default('new');                 // new / contacted / pre-approved / disbursed / lost
            $table->text('notes')->nullable();                        // admin internal notes

            // Metadata
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_leads');
    }
};
