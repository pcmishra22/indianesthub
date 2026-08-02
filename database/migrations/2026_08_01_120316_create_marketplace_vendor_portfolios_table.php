<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_vendor_portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('marketplace_vendors')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image');
            $table->date('completed_at')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_vendor_portfolios');
    }
};
