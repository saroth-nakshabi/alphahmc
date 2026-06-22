<?php

namespace App\Support;

/**
 * Render-time responsive image helper (native GD — no external library).
 *
 * Img::thumb('uploads/project_images/x.jpg', 800) returns the URL of a cached,
 * down-scaled copy of the image (same format, never upscaled), generating it on
 * first call and serving the static file thereafter. The .htaccess 1-year cache
 * applies to the generated files automatically (they live under public/uploads/).
 *
 * Implemented with PHP's bundled GD extension rather than intervention/image,
 * because Intervention v4.1 uses PHP 8.3+ syntax and the production/runtime PHP
 * is 8.2 — Intervention parse-errors there. Native GD works on PHP 8.0+.
 *
 * SAFETY: every failure path (no GD, unreadable file, non-raster format, encode
 * error) returns the ORIGINAL image URL, so a page can never show a broken image
 * because of this helper. Display size is unchanged — CSS still controls layout;
 * we only shrink the bytes downloaded. No upload-controller changes are needed:
 * a freshly uploaded image is optimised the first time a page renders it.
 */
class Img
{
    /** Raster formats we will resize. Everything else is passed through untouched. */
    private const RASTER = ['jpg', 'jpeg', 'png'];

    /**
     * @param  string $path     Path relative to the public dir, e.g. "uploads/service_images/x.jpg"
     *                          (a leading "public/" or "/" is tolerated and stripped).
     * @param  int    $width    Max width in px. The image is only ever scaled DOWN.
     * @param  int    $quality  JPEG quality 0-100 (mapped to a sane PNG compression level too).
     * @return string Absolute URL — the derivative if it could be made, else the original.
     */
    public static function thumb(string $path, int $width, int $quality = 80): string
    {
        $rel      = ltrim($path, '/');
        $rel      = preg_replace('#^public/#', '', $rel);   // normalise to "uploads/..."
        $original = asset('public/' . $rel);

        try {
            $src = public_path($rel);
            if (!is_file($src)) {
                return $original;
            }

            $ext = strtolower(pathinfo($rel, PATHINFO_EXTENSION));
            if (!in_array($ext, self::RASTER, true) || !extension_loaded('gd')) {
                return $original;                            // svg/gif/webp or no GD → leave alone
            }

            // Cache key changes when the source file or the requested size changes.
            $key      = md5($rel . '|' . filemtime($src) . '|' . $width . '|' . $quality);
            $cacheRel = 'uploads/cache/' . $key . '.' . $ext;
            $cacheAbs = public_path($cacheRel);

            if (is_file($cacheAbs)) {
                return asset('public/' . $cacheRel);
            }

            $dir = dirname($cacheAbs);
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            if (!is_dir($dir) || !is_writable($dir)) {
                return $original;                            // can't cache → serve original
            }

            if (!self::generate($src, $cacheAbs, $cacheRel, $ext, $width, $quality)) {
                return $original;
            }

            // Never serve a derivative that ended up larger than the source
            // (can happen re-encoding an already-optimised image with no downscale).
            if (@filesize($cacheAbs) >= @filesize($src)) {
                @unlink($cacheAbs);
                return $original;
            }

            return asset('public/' . $cacheRel);
        } catch (\Throwable $e) {
            return $original;                                // any failure → original, never break
        }
    }

    /** Create the resized derivative with GD. Returns true on success. */
    private static function generate(string $src, string $dest, string $cacheRel, string $ext, int $width, int $quality): bool
    {
        $info = @getimagesize($src);
        if (!$info) {
            return false;
        }
        [$srcW, $srcH] = $info;
        if ($srcW < 1 || $srcH < 1) {
            return false;
        }

        $isPng = ($ext === 'png');

        $source = $isPng ? @imagecreatefrompng($src) : @imagecreatefromjpeg($src);
        if (!$source) {
            return false;
        }

        // Scale DOWN only — never enlarge a smaller source.
        $dstW = $srcW > $width ? $width : $srcW;
        $dstH = (int) round($srcH * ($dstW / $srcW));
        if ($dstH < 1) {
            $dstH = 1;
        }

        $canvas = imagecreatetruecolor($dstW, $dstH);
        if (!$canvas) {
            imagedestroy($source);
            return false;
        }

        if ($isPng) {
            // Preserve transparency.
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
            imagefilledrectangle($canvas, 0, 0, $dstW, $dstH, $transparent);
        }

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $dstW, $dstH, $srcW, $srcH);

        if ($isPng) {
            // GD PNG "quality" is a 0-9 compression level (9 = smallest). Map from 0-100.
            $level = (int) round((100 - $quality) / 100 * 9);
            $level = max(0, min(9, $level));
            $ok = imagepng($canvas, $dest, $level);
        } else {
            $ok = imagejpeg($canvas, $dest, $quality);
        }

        imagedestroy($source);
        imagedestroy($canvas);

        return (bool) $ok && is_file($dest);
    }
}
