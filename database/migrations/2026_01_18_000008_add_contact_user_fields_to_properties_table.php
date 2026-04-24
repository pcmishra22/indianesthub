<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (!Schema::hasColumn('properties', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            }
            if (!Schema::hasColumn('properties', 'contact_name')) {
                $table->string('contact_name', 100)->nullable();
            }
            if (!Schema::hasColumn('properties', 'contact_phone')) {
                $table->string('contact_phone', 30)->nullable();
            }
            if (!Schema::hasColumn('properties', 'contact_email')) {
                $table->string('contact_email', 100)->nullable();
            }
            if (!Schema::hasColumn('properties', 'company_name')) {
                $table->string('company_name', 100)->nullable();
            }
            if (!Schema::hasColumn('properties', 'license_number')) {
                $table->string('license_number', 50)->nullable();
            }
            if (!Schema::hasColumn('properties', 'verified_user')) {
                $table->boolean('verified_user')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            if (Schema::hasColumn('properties', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('properties', 'contact_name')) {
                $table->dropColumn('contact_name');
            }
            if (Schema::hasColumn('properties', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
            if (Schema::hasColumn('properties', 'contact_email')) {
                $table->dropColumn('contact_email');
            }
            if (Schema::hasColumn('properties', 'company_name')) {
                $table->dropColumn('company_name');
            }
            if (Schema::hasColumn('properties', 'license_number')) {
                $table->dropColumn('license_number');
            }
            if (Schema::hasColumn('properties', 'verified_user')) {
                $table->dropColumn('verified_user');
            }
        });
    }
};
