<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builders', function (Blueprint $table) {
            $table->string('rera_registration')->nullable()->after('established_year');
            $table->text('cities_operating')->nullable()->after('rera_registration');
            $table->decimal('rating', 3, 2)->default(0)->after('cities_operating');
            $table->boolean('is_verified')->default(false)->after('rating');
            $table->integer('total_delivered_projects')->default(0)->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('builders', function (Blueprint $table) {
            $table->dropColumn(['rera_registration', 'cities_operating', 'rating', 'is_verified', 'total_delivered_projects']);
        });
    }
};
