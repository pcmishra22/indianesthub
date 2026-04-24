<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('revenue_reports', function (Blueprint $table) {
            $table->id();
            $table->string('period');
            $table->decimal('total_revenue', 12, 2);
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('revenue_reports');
    }
};
