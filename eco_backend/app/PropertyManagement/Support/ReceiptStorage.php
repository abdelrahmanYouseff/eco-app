<?php

namespace App\PropertyManagement\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ReceiptStorage
{
    public static function normalizePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        $path = str_replace('\\', '/', $path);
        $path = ltrim($path, '/');

        foreach (['storage/app/public/', 'app/public/', 'public/'] as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $path = substr($path, strlen($prefix));
            }
        }

        return $path ?: null;
    }

    /**
     * @return array<int, string>
     */
    public static function candidateAbsolutePaths(?string $storedPath): array
    {
        $relative = self::normalizePath($storedPath);
        if (!$relative) {
            return [];
        }

        $candidates = [
            storage_path('app/public/' . $relative),
        ];

        try {
            $candidates[] = Storage::disk('public')->path($relative);
        } catch (\Throwable) {
            // ignore if adapter has no path()
        }

        $sharedRoot = config('filesystems.shared_public_root');
        if ($sharedRoot) {
            $candidates[] = rtrim($sharedRoot, '/') . '/' . $relative;
        }

        return array_values(array_unique($candidates));
    }

    public static function exists(?string $storedPath): bool
    {
        return self::resolveAbsolutePath($storedPath) !== null;
    }

    public static function resolveAbsolutePath(?string $storedPath): ?string
    {
        foreach (self::candidateAbsolutePaths($storedPath) as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        $relative = self::normalizePath($storedPath);
        if (!$relative) {
            return null;
        }

        $fromRelease = self::findInForgeReleases($relative);
        if ($fromRelease) {
            self::promoteToActiveStorage($relative, $fromRelease);

            foreach (self::candidateAbsolutePaths($storedPath) as $path) {
                if (is_file($path)) {
                    return $path;
                }
            }

            return $fromRelease;
        }

        return null;
    }

    /**
     * Copy a receipt from an old Forge release into active + shared storage.
     */
    public static function promoteToActiveStorage(string $relative, string $sourcePath): bool
    {
        $copied = false;

        foreach (self::promotionDestinations($relative) as $destination) {
            if (is_file($destination)) {
                $copied = true;
                continue;
            }

            $dir = dirname($destination);
            if (!is_dir($dir) && !@mkdir($dir, 0755, true) && !is_dir($dir)) {
                Log::warning('Could not create receipts directory for promote', ['dir' => $dir]);
                continue;
            }

            if (@copy($sourcePath, $destination)) {
                $copied = true;
                Log::info('Receipt promoted', [
                    'relative' => $relative,
                    'from' => $sourcePath,
                    'to' => $destination,
                ]);
            }
        }

        return $copied;
    }

    /**
     * @return array<int, string>
     */
    private static function promotionDestinations(string $relative): array
    {
        $destinations = [
            storage_path('app/public/' . $relative),
        ];

        $sharedRoot = config('filesystems.shared_public_root');
        if ($sharedRoot) {
            $destinations[] = rtrim($sharedRoot, '/') . '/' . $relative;
        }

        return array_values(array_unique($destinations));
    }

    /**
     * Forge: receipts may live under an old release folder while DB path is correct.
     */
    public static function findInForgeReleases(string $relative): ?string
    {
        $siteRoot = self::forgeSiteRoot();
        if (!$siteRoot) {
            return null;
        }

        $patterns = [
            rtrim($siteRoot, '/') . '/releases/*/storage/app/public/' . $relative,
            rtrim($siteRoot, '/') . '/releases/*/storage/app/public/receipts/' . basename($relative),
        ];

        $matches = [];
        foreach ($patterns as $pattern) {
            $matches = array_merge($matches, glob($pattern) ?: []);
        }

        $matches = array_values(array_unique($matches));
        if ($matches === []) {
            return null;
        }

        usort($matches, static fn (string $a, string $b): int => filemtime($b) <=> filemtime($a));

        foreach ($matches as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }

    public static function forgeSiteRoot(): ?string
    {
        $configured = env('FORGE_SITE_PATH');
        if ($configured) {
            return rtrim($configured, '/');
        }

        $sharedRoot = config('filesystems.shared_public_root');
        if ($sharedRoot) {
            return dirname(dirname(dirname(rtrim($sharedRoot, '/'))));
        }

        $base = realpath(base_path()) ?: base_path();
        if (preg_match('#^(.+)/(releases/\d+|current)$#', $base, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return array{found: bool, source: ?string, destinations: array<string, bool>, site_root: ?string}
     */
    public static function diagnose(?string $storedPath): array
    {
        $relative = self::normalizePath($storedPath);
        $result = [
            'found' => false,
            'source' => null,
            'destinations' => [],
            'site_root' => self::forgeSiteRoot(),
            'checked' => self::candidateAbsolutePaths($storedPath),
        ];

        if (!$relative) {
            return $result;
        }

        foreach ($result['checked'] as $path) {
            if (is_file($path)) {
                $result['found'] = true;
                $result['source'] = $path;
                break;
            }
        }

        if (!$result['found']) {
            $fromRelease = self::findInForgeReleases($relative);
            if ($fromRelease) {
                $result['source'] = $fromRelease;
                self::promoteToActiveStorage($relative, $fromRelease);
            }
        }

        foreach (self::promotionDestinations($relative) as $dest) {
            $result['destinations'][$dest] = is_file($dest);
            if ($result['destinations'][$dest]) {
                $result['found'] = true;
            }
        }

        if ($result['found'] && !$result['source']) {
            foreach ($result['destinations'] as $dest => $exists) {
                if ($exists) {
                    $result['source'] = $dest;
                    break;
                }
            }
        }

        return $result;
    }
}
