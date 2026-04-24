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
        Schema::table('property_dealers', function (Blueprint $table) {
            $table->string('profile_photo')->nullable()->after('password');
            $table->text('bio')->nullable()->after('profile_photo');
            $table->string('specializations')->nullable()->after('bio');
            $table->string('operating_cities')->nullable()->after('specializations');
            $table->enum('status', ['active', 'blocked', 'pending'])->default('active')->after('operating_cities');
        });
    }

    public function down(): void
    {
        Schema::table('property_dealers', function (Blueprint $table) {
            $table->dropColumn(['profile_photo', 'bio', 'specializations', 'operating_cities', 'status']);
        });
    }
};
