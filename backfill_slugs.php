App\Models\BuilderProject::whereNull('slug')->get()->each(function ($p) {
    $p->slug = App\Models\BuilderProject::generateUniqueSlug($p);
    $p->saveQuietly();
});

echo App\Models\BuilderProject::whereNull('slug')->count() . ' remaining null slugs' . PHP_EOL;
