<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->string('category')->default('news'); // news, tips, guides, market-update
            $table->string('author')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->integer('views_count')->default(0);
            $table->json('tags')->nullable();
            $table->timestamps();
            
            $table->index(['is_published', 'published_at']);
            $table->index('category');
        });
    }

    public function down() {
        Schema::dropIfExists('blog_posts');
    }
};
