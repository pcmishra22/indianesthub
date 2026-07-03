<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Lets admin see, on the builder/project viewers page, which visitor
     * (by guest cookie token) actually submitted an enquiry — same pattern
     * already used to link Inquiry rows to PropertyView rows.
     */
    public function up(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            if (!Schema::hasColumn('builder_leads', 'visitor_token')) {
                $table->string('visitor_token')->nullable()->after('user_agent');
                $table->index('visitor_token');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('builder_leads', function (Blueprint $table) {
            if (Schema::hasColumn('builder_leads', 'visitor_token')) {
                $table->dropIndex(['visitor_token']);
                $table->dropColumn('visitor_token');
            }
        });
    }
};
