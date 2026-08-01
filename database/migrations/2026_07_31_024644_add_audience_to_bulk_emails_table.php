<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bulk_emails', function (Blueprint $table) {
            if (!Schema::hasColumn('bulk_emails', 'audience')) {
                // Existing rows default to 'users' since that was the only
                // audience the "Users Bulk Email" screen (where most drafts
                // were likely created) could ever actually send to. Any
                // draft that was really intended for dealers should be
                // double-checked and re-selected before queueing, since the
                // old shared-table design had no way to record that intent.
                $table->string('audience')->default('users')->after('body');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bulk_emails', function (Blueprint $table) {
            $table->dropColumn('audience');
        });
    }
};
