<?php

namespace App\Console\Commands;

use App\Models\BuilderProject;
use Illuminate\Console\Command;

class GenerateProjectSlugs extends Command
{
    protected $signature   = 'projects:generate-slugs {--force : Regenerate slugs even if already set}';
    protected $description = 'Generate SEO slugs for all existing builder projects';

    public function handle(): int
    {
        $query = BuilderProject::query();

        if (! $this->option('force')) {
            $query->whereNull('slug')->orWhere('slug', '');
        }

        $projects = $query->get();

        if ($projects->isEmpty()) {
            $this->info('No projects need slug generation.');
            return 0;
        }

        $this->info("Generating slugs for {$projects->count()} project(s)...");
        $bar = $this->output->createProgressBar($projects->count());
        $bar->start();

        foreach ($projects as $project) {
            $project->slug = BuilderProject::generateUniqueSlug($project);
            $project->saveQuietly(); // skip model events to avoid re-triggering
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Done! All project slugs generated.');

        return 0;
    }
}
