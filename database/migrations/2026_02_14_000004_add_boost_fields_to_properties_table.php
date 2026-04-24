<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('is_boosted')->default(false)->after('is_premium');
            $table->timestamp('boosted_until')->nullable()->after('is_boosted');
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['is_boosted', 'boosted_until']);
        });
    }
};
