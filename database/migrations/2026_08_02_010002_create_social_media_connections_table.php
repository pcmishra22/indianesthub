<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('social_media_connections', function (Blueprint $table) {
            $table->id();

            // Polymorphic owner: Dealer or Builder
            $table->string('connectable_type');
            $table->unsignedBigInteger('connectable_id');

            $table->string('platform')->default('facebook'); // facebook (covers linked Instagram too)
            $table->string('page_id');
            $table->string('page_name')->nullable();
            $table->string('page_category')->nullable();
            $table->string('ig_business_id')->nullable();     // Instagram professional account linked to this Page, if any
            $table->string('ig_username')->nullable();

            // Encrypted at the application layer (Laravel 'encrypted' cast).
            $table->text('page_access_token');

            $table->string('connected_by_name')->nullable();  // Facebook user who authorized this
            $table->boolean('is_active')->default(true);
            $table->boolean('leadgen_subscribed')->default(false);
            $table->timestamp('last_lead_at')->nullable();
            $table->text('last_error')->nullable();

            $table->timestamps();

            $table->index(['connectable_type', 'connectable_id']);
            $table->unique(['connectable_type', 'connectable_id', 'page_id']);
            $table->index('page_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_media_connections');
    }
};
