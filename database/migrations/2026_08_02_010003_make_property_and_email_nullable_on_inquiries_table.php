<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->text('message')->nullable()->change();
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreignId('property_id')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->text('message')->nullable(false)->change();
        });

        Schema::table('inquiries', function (Blueprint $table) {
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
    }
};
