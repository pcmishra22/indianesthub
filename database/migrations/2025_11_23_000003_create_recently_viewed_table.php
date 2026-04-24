<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('recently_viewed', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // For non-logged-in users
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->timestamps();
            
            $table->index(['user_id', 'viewed_at']);
            $table->index(['session_id', 'viewed_at']);
        });
    }

    public function down() {
        Schema::dropIfExists('recently_viewed');
    }
};
