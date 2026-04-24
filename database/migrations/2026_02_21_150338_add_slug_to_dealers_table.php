<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Dealer;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('property_dealers', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Generate slugs for existing dealers
        Dealer::all()->each(function ($dealer) {
            $baseName = trim(($dealer->first_name ?? '') . ' ' . ($dealer->last_name ?? ''));
            if (!$baseName) {
                $baseName = $dealer->company_name ?? 'dealer';
            }
            $slug = Str::slug($baseName);

            // Ensure uniqueness
            $original = $slug;
            $count = 1;
            while (Dealer::where('slug', $slug)->where('id', '!=', $dealer->id)->exists()) {
                $slug = $original . '-' . $count++;
            }

            $dealer->updateQuietly(['slug' => $slug]);
        });
    }

    public function down(): void
    {
        Schema::table('property_dealers', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
