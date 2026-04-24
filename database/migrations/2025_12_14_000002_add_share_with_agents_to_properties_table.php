<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('properties', function (Blueprint $table) {
            $table->boolean('share_with_agents')->default(false)->after('security_deposit');
        });
    }
    public function down() {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('share_with_agents');
        });
    }
};
