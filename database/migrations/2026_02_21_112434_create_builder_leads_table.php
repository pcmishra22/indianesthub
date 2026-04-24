<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builder_leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('builder_id')->constrained('builders')->cascadeOnDelete();
            $table->foreignId('builder_project_id')->nullable()->constrained('builder_projects')->nullOnDelete();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->text('message')->nullable();
            $table->string('lead_type')->default('general'); // general / visit / callback / brochure / whatsapp
            $table->string('source')->default('website');    // website / google / facebook / whatsapp
            $table->string('status')->default('new');        // new / contacted / converted / lost
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('builder_leads');
    }
};
