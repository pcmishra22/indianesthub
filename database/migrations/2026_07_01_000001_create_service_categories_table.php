<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');              // e.g. "Electrician"
            $table->string('slug')->unique();    // e.g. "electrician"
            $table->string('icon')->nullable();  // bootstrap-icons class, e.g. "bi-lightning-charge"
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed common trades/services so the dropdown isn't empty on day one.
        $now = now();
        Schema::table('service_categories', function () {}); // no-op to keep schema closed before insert
        \Illuminate\Support\Facades\DB::table('service_categories')->insert([
            ['name' => 'Interior Designer', 'slug' => 'interior-designer', 'icon' => 'bi-palette',        'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Electrician',       'slug' => 'electrician',       'icon' => 'bi-lightning-charge','sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Plumber',           'slug' => 'plumber',           'icon' => 'bi-wrench',         'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mistry / Mason',    'slug' => 'mistry-mason',      'icon' => 'bi-bricks',         'sort_order' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Painter',           'slug' => 'painter',           'icon' => 'bi-brush',          'sort_order' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Carpenter',         'slug' => 'carpenter',         'icon' => 'bi-hammer',         'sort_order' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Packers & Movers',  'slug' => 'packers-movers',    'icon' => 'bi-truck',          'sort_order' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Home Loan Provider','slug' => 'home-loan-provider','icon' => 'bi-bank',           'sort_order' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Insurance Agent',   'slug' => 'insurance-agent',   'icon' => 'bi-shield-check',   'sort_order' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Legal Advisor',     'slug' => 'legal-advisor',     'icon' => 'bi-briefcase',      'sort_order' => 10,'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Vastu Consultant',  'slug' => 'vastu-consultant',  'icon' => 'bi-compass',        'sort_order' => 11,'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pest Control',      'slug' => 'pest-control',      'icon' => 'bi-bug',            'sort_order' => 12,'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
