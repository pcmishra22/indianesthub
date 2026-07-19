<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Home Marketplace — product images (gallery)
 *
 * Each product has 1 cover (denormalized on products.cover_image) and
 * 0..N gallery images. Stored separately so the listing query stays fast
 * and we can reorder the gallery without touching the product row.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketplace_product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('marketplace_products')
                ->cascadeOnDelete();
            $table->string('image_path', 255);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketplace_product_images');
    }
};
