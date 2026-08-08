<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auction_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('auction_id')->constrained('auctions')->cascadeOnDelete();

            // sale_deed | ownership_proof | loan_noc | encumbrance_certificate |
            // site_map | property_tax_receipt | identity_proof | other
            $table->string('document_type');
            $table->string('title')->nullable(); // free-text label, mainly used for "other"
            $table->string('file_path');
            $table->string('original_filename')->nullable();

            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->text('admin_remarks')->nullable();
            $table->unsignedBigInteger('reviewed_by_admin_id')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['auction_id', 'document_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auction_documents');
    }
};
