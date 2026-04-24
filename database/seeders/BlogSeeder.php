<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('blog_posts')->insert([
            [
                'title' => 'How to Buy Your First Home',
                'slug' => Str::slug('How to Buy Your First Home'),
                'content' => 'A step-by-step guide to buying your first property.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Top 10 Real Estate Investment Tips',
                'slug' => Str::slug('Top 10 Real Estate Investment Tips'),
                'content' => 'Maximize your returns with these proven strategies.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Understanding Property Taxes in India',
                'slug' => Str::slug('Understanding Property Taxes in India'),
                'content' => 'Everything you need to know about property taxes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
