<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('email_trackings', function (Blueprint $table) {
            $table->id();
            $table->string('email_type')->default('proposal');   // proposal, renewal, etc.
            $table->string('recipient_email');
            $table->string('recipient_name');
            $table->string('recipient_type')->default('dealer'); // dealer | builder
            $table->string('token', 64)->unique();               // unique per email sent
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('first_opened_at')->nullable();
            $table->unsignedInteger('open_count')->default(0);
            $table->string('last_ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_trackings');
    }
};
