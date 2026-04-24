<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_engagements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('action');
            $table->text('details')->nullable();
            $table->timestamp('engaged_at');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_engagements');
    }
};
