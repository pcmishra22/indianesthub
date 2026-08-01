<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_token', 64)->unique(); // client-side session id (cookie/localStorage)

            // Lead capture — filled in once the visitor shares details or the AI extracts intent
            $table->string('name')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email')->nullable();

            // Context
            $table->unsignedBigInteger('property_id')->nullable(); // property page the chat started from, if any
            $table->string('source_page')->nullable();
            $table->string('status')->default('open'); // open / lead-captured / closed
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamp('last_message_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('phone');
        });

        Schema::create('ai_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ai_chat_session_id');
            $table->string('role', 20); // user / assistant
            $table->text('content');
            $table->timestamps();

            $table->foreign('ai_chat_session_id')->references('id')->on('ai_chat_sessions')->onDelete('cascade');
            $table->index('ai_chat_session_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_chat_messages');
        Schema::dropIfExists('ai_chat_sessions');
    }
};
