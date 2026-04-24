<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\Builder;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('builders', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('id');
        });

        // Generate slugs for existing builders
        Builder::all()->each(function ($builder) {
            $baseName = $builder->company_name ?: $builder->name ?: 'builder';
            $slug = Str::slug($baseName);

            // Ensure uniqueness
            $original = $slug;
            $count = 1;
            while (Builder::where('slug', $slug)->where('id', '!=', $builder->id)->exists()) {
                $slug = $original . '-' . $count++;
            }

            $builder->updateQuietly(['slug' => $slug]);
        });
    }

    public function down(): void
    {
        Schema::table('builders', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
