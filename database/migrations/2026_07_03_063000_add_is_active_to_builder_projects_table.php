<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('builder_projects', function (Blueprint $table) {
            // Lets admin hide/show a project on the public site without deleting it.
            // Defaults to true so existing projects stay visible after this migration runs.
            $table->boolean('is_active')->default(true)->after('is_featured');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('builder_projects', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
