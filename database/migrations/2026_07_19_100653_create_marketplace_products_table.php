<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Home Marketplace — products
 *
 * A "product" in Phase 1 is really a vendor capability card, not a SKU.
 * We carry a price range (min/max) and a BHK fit so the property page
 * widget can match the right products to the right home.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')
                ->constrained('marketplace_vendors')
                ->cascadeOnDelete();
            $table->foreignId('category_id')
                ->constrained('marketplace_categories')
                ->restrictOnDelete();
            $table->string('name', 200);
            $table->string('slug', 220)->unique();
            $table->text('description')->nullable();
            $table->json('bhk_fit')->nullable();         // e.g. ["1","2","3","4"] — BHK types this fits
            $table->decimal('price_min', 12, 2)->nullable();
            $table->decimal('price_max', 12, 2)->nullable();
            $table->string('price_unit', 30)->default('onwards'); // e.g. "per panel", "per set"
            $table->json('tags')->nullable();            // e.g. ["eyelet","sheer","blackout"]
            $table->string('cover_image', 255)->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('leads_count')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'category_id']);
            $table->index('vendor_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_products');
    }
};
