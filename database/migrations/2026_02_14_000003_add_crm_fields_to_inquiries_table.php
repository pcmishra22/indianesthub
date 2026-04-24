<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->string('status')->default('New')->after('message');
            $table->text('notes')->nullable()->after('status');
            $table->string('tags')->nullable()->after('notes');
            $table->unsignedBigInteger('assigned_agent_id')->nullable()->after('tags');
        });
    }

    public function down()
    {
        Schema::table('inquiries', function (Blueprint $table) {
            $table->dropColumn(['status', 'notes', 'tags', 'assigned_agent_id']);
        });
    }
};
