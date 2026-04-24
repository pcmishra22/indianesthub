<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->foreignId('broker_id')->constrained('property_dealers')->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->text('message');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('inquiries');
    }
};
