<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_trackings', function (Blueprint $table) {
            // 'sent' = delivered to SMTP, 'failed' = SMTP rejected, 'pending' = not yet attempted
            $table->string('status')->default('sent')->after('token');
            $table->text('failure_reason')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('email_trackings', function (Blueprint $table) {
            $table->dropColumn(['status', 'failure_reason']);
        });
    }
};
