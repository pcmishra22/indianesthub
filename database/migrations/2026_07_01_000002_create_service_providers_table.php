<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_providers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('business_name')->nullable();
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('slug')->unique();

            $table->string('profile_photo')->nullable();
            $table->text('bio')->nullable();
            $table->unsignedSmallInteger('years_experience')->nullable();

            $table->string('city')->nullable();
            $table->json('operating_areas')->nullable();  // ["Zirakpur","Mohali","Chandigarh"]

            $table->decimal('starting_price', 10, 2)->nullable();
            $table->string('price_unit')->nullable();      // e.g. "per sqft", "per visit", "per project"

            // Flexible bucket for category-specific extra fields
            // e.g. {"banks_partnered":["SBI","HDFC"]} or {"portfolio_images":[...]}
            $table->json('meta')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected', 'suspended'])->default('pending');
            $table->boolean('is_verified')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('service_provider_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['service_provider_id', 'service_category_id'], 'sp_category_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_provider_category');
        Schema::dropIfExists('service_providers');
    }
};
