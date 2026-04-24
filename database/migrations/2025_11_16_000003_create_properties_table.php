<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_dealer_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('property_type');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->decimal('price', 15, 2);
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area')->nullable();
            $table->string('furnishing')->nullable();
            $table->string('amenities')->nullable();
            $table->string('status')->default('Available');
            $table->timestamps();
            $table->foreign('property_dealer_id')->references('id')->on('property_dealers')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('properties');
    }
};
