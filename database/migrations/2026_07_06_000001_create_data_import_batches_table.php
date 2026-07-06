<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_import_batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('city');
            $table->enum('type', ['builder', 'agent', 'property']);
            $table->string('source')->default('google_places'); // google_places | manual_csv
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->json('payload'); // raw candidate records returned by the discovery step
            $table->string('summary')->nullable(); // e.g. "8 inserted, 2 skipped (duplicates)"
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_import_batches');
    }
};
