<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('lead_sources', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id');
            $table->string('source');
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_sources');
    }
};
