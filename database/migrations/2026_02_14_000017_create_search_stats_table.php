<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('search_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('query');
            $table->integer('results_count');
            $table->timestamp('searched_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('search_stats');
    }
};
