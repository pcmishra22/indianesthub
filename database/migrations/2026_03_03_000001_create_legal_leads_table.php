<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_leads', function (Blueprint $table) {
            $table->id();

            // Context — which property triggered this lead
            $table->unsignedBigInteger('property_id')->nullable();
            $table->unsignedBigInteger('builder_project_id')->nullable();

            // Applicant
            $table->string('name');
            $table->string('phone', 20);
            $table->string('email')->nullable();

            // Legal details
            $table->string('legal_issue_type')->default('other');   // property_dispute / title_verification / sale_deed / will_registration / rental_agreement / court_case / other
            $table->text('description')->nullable();                 // brief description of the issue
            $table->date('preferred_date')->nullable();              // preferred consultation date
            $table->string('city')->nullable();                      // city of the property / dispute

            // Tracking
            $table->string('source')->default('website');
            $table->string('source_page')->nullable();
            $table->string('status')->default('new');               // new / contacted / consultation_scheduled / resolved / closed
            $table->text('notes')->nullable();                      // admin internal notes

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
        Schema::dropIfExists('legal_leads');
    }
};
