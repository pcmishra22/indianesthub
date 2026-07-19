<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Home Marketplace — vendors
 *
 * Local shops that list products. NOT users — they don't log in.
 * Lead routing uses the WhatsApp number. Phone is the primary contact.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name', 160);
            $table->string('slug', 180)->unique();
            $table->string('owner_name', 120)->nullable();
            $table->string('phone', 20);                  // primary contact, used for WhatsApp
            $table->string('whatsapp', 20)->nullable();   // optional separate WhatsApp number
            $table->string('email', 160)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('area', 120)->nullable();
            $table->string('address', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('logo', 255)->nullable();
            $table->unsignedSmallInteger('years_in_business')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_active')->default(true);
            $table->decimal('commission_pct', 5, 2)->default(8.00);  // default 8%
            $table->timestamps();

            $table->index(['is_active', 'is_verified']);
            $table->index('city');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_vendors');
    }
};
