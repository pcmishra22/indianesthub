<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name'); // User-given name for this search
            $table->json('filters'); // Store search criteria as JSON
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('saved_searches');
    }
};
