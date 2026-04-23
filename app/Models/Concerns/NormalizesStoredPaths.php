<?php

namespace App\Models\Concerns;

trait NormalizesStoredPaths
{
    public static function normalizeStoredPathValue(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        $normalized = str_replace('\\', '/', $path);
        $normalized = preg_replace('#^/?public/#', '', $normalized);
        $normalized = preg_replace('#^/?storage/#', '', $normalized);

        return ltrim($normalized, '/');
    }

    public function storageAssetUrl(?string $path): ?string
    {
        $normalized = static::normalizeStoredPathValue($path);

        if (blank($normalized)) {
            return null;
        }

        if (filter_var($normalized, FILTER_VALIDATE_URL)) {
            return $normalized;
        }

        return asset('storage/' . $normalized);
    }
}
