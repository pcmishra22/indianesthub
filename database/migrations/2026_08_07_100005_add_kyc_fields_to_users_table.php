<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pan_number', 10)->nullable()->after('phone');
            $table->string('kyc_id_proof_path')->nullable()->after('pan_number');

            // not_submitted | pending | verified | rejected
            $table->string('kyc_status')->default('not_submitted')->after('kyc_id_proof_path');
            $table->timestamp('kyc_submitted_at')->nullable()->after('kyc_status');
            $table->timestamp('kyc_verified_at')->nullable()->after('kyc_submitted_at');
            $table->text('kyc_rejection_reason')->nullable()->after('kyc_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'pan_number', 'kyc_id_proof_path', 'kyc_status',
                'kyc_submitted_at', 'kyc_verified_at', 'kyc_rejection_reason',
            ]);
        });
    }
};
