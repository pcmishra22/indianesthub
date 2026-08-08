<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Nullable-unique: most users never submit a PAN, but once one is
            // submitted it can't be reused on a second account — this is the
            // simplest guard against "seller creates a second account to bid
            // against themselves."
            $table->unique('pan_number');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['pan_number']);
        });
    }
};
