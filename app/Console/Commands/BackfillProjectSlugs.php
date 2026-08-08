<?php

namespace App\Console\Commands;

use App\Models\BuilderProject;
use Illuminate\Console\Command;

class BackfillProjectSlugs extends Command
{
    protected $signature = 'projects:backfill-slugs';
    protected $description = 'Fill in missing slugs on builder_projects rows';

    public function handle(): int
    {
        $missing = BuilderProject::whereNull('slug')->count();
        $this->info("Found {$missing} projects with no slug.");

        BuilderProject::whereNull('slug')->get()->each(function (BuilderProject $p) {
            $p->slug = BuilderProject::generateUniqueSlug($p);
            $p->saveQuietly();
            $this->line("  #{$p->id} -> {$p->slug}");
        });

        $remaining = BuilderProject::whereNull('slug')->count();
        $this->info("Done. Remaining null slugs: {$remaining}");

        return self::SUCCESS;
    }
}
