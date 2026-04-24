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
        Schema::create('builder_projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('builder_id')->constrained('builders')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('project_type')->default('Residential'); // Residential/Commercial/Plotted/Township/Mixed Use
            $table->string('status')->default('Upcoming');           // Upcoming/Under Construction/Ready to Move/Completed
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('total_units')->nullable();
            $table->integer('available_units')->nullable();
            $table->decimal('price_from', 15, 2)->nullable();
            $table->decimal('price_to', 15, 2)->nullable();
            $table->date('possession_date')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->text('amenities')->nullable();
            $table->string('rera_id')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builder_projects');
    }
};
