<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class AutoSlugHelper
{
    /**
     * Generate a unique slug from a given string, ensuring uniqueness
     * by appending increments if needed.
     */
    public static function generate(
        string $subject,
        string $table,
        string $slugColumn = 'slug',
        int $maxLength = 255
    ): string {
        $slug = Str::slug($subject);
        $baseSlug = $slug;
        $counter = 1;

        while (self::existsInTable($table, $slugColumn, $slug)) {
            $slug = $baseSlug.'-'.$counter++;
        }

        return mb_substr($slug, 0, $maxLength);
    }

    private static function existsInTable(
        string $table,
        string $column,
        string $value
    ): bool {
        return \DB::table($table)->where($column, $value)->exists();
    }
}
