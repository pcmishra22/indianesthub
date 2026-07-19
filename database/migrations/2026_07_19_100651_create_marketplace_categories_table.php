<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Home Marketplace — categories
 *
 * Lightweight seedable list (Curtains, Lights, Furniture, Kitchen, etc.).
 * Slug is the public key used to filter on the property page widget.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80);
            $table->string('slug', 80)->unique();
            $table->string('icon', 60)->nullable();      // bootstrap-icons class e.g. bi-lamp
            $table->string('tagline', 160)->nullable();   // short marketing line
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_categories');
    }
};
