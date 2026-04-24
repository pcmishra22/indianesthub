<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('localities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->string('state');
            $table->text('description')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('safety_score')->default(0); // 0-100
            $table->json('nearby_amenities')->nullable(); // schools, hospitals, malls, etc.
            $table->json('transport')->nullable(); // metro, bus, etc.
            $table->decimal('avg_price_per_sqft', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index(['city', 'name']);
        });
    }

    public function down() {
        Schema::dropIfExists('localities');
    }
};
