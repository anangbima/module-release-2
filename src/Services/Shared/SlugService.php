<?php 

namespace Modules\ModuleRelease2\Services\Shared;

use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a unique slug for the given model based on the provided name.
     *
     * @param  string  $name        The base name to generate the slug from (usually a title or name field).
     * @param  string  $modelClass  The fully qualified class name of the Eloquent model.
     * @param  string  $column      The column name where the slug is stored (default is 'slug').
     * @return string               A unique slug string.
     */
    public function generate(string $name, string $modelClass, string $column = 'slug'): string
    {
        $slug = Str::slug($name, '-');

        // Count existing slugs that start with the same base
        // e.g. if "my-title" already exists, it becomes "my-title-1", "my-title-2", etc.
        $count = $modelClass::where($column, 'like', "{$slug}%")->count();

        // Append count if there's a duplicate
        return $count ? "{$slug}-{$count}" : $slug;
    }
}