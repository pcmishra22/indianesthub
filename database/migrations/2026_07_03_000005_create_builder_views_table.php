<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Mirrors the property_views table so admin gets the same visitor
     * tracking (who / when / device / browser / guest-or-logged-in) for
     * builder profile page visits.
     */
    public function up(): void
    {
        Schema::create('builder_views', function (Blueprint $table) {
            $table->id();

            if (Schema::hasTable('builders')) {
                $table->foreignId('builder_id')->constrained('builders')->cascadeOnDelete();
            } else {
                $table->unsignedBigInteger('builder_id');
            }

            $table->string('event_type', 30)->default('page_view'); // page_view, lead_submit
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();
            $table->string('visitor_token')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('device', 20)->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('referrer', 500)->nullable();
            $table->string('page_url', 500)->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamps();

            $table->index(['builder_id', 'event_type']);
            $table->index('visitor_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('builder_views');
    }
};
