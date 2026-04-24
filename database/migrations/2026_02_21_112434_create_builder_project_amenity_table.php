<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builder_project_amenity', function (Blueprint $table) {
            $table->id();
            $table->foreignId('builder_project_id')->constrained('builder_projects')->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete();
            $table->unique(['builder_project_id', 'amenity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('builder_project_amenity');
    }
};
