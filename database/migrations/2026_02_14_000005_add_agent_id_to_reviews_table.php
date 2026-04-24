<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // agent_id already exists in reviews table, no action needed
    }

    public function down()
    {
        // agent_id already exists in reviews table, no action needed
    }
};
