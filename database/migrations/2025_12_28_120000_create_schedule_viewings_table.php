<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('schedule_viewings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('dealer_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->date('date');
            $table->string('time');
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('dealer_id')->references('id')->on('property_dealers')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('schedule_viewings');
    }
};
