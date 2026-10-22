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
        return self::derive($path, $width, $quality, null) ?? asset('public/' . preg_replace('#^public/#', '', ltrim($path, '/')));
    }

    /**
     * WebP copy of a jpg/png, scaled down to $width like thumb(). Returns null when it cannot be
     * made (no WebP in GD, missing file, other format), so callers can simply leave the WebP
     * source out and the browser uses the jpg/png.
     */
    public static function webp(string $path, int $width, int $quality = 70): ?string
    {
        if (!function_exists('imagewebp')) {
            return null;
        }

        return self::derive($path, $width, $quality, 'webp');
    }

    /**
     * Hero image as a <picture> for service, group and category pages (the LCP element):
     * phones (up to 767px) get the 412/828 files, larger screens 1280/1920, each as WebP with a
     * jpg/png fallback. Files are never upscaled; repeated URLs are listed once.
     *
     * @param  string|null $path         Upload path as for thumb(), or null when there is none.
     * @param  string      $fallbackPath Path under public/ used when $path is empty.
     * @return array{phone: array{webp: string, img: string}, desktop: array{webp: string, img: string}, src: string, width: ?int, height: ?int}
     */
    public static function heroPicture(?string $path, string $fallbackPath): array
    {
        $path = ($path === null || trim($path) === '') ? $fallbackPath : $path;
        $set  = function (array $widths, bool $webp) use ($path): string {
            $parts = [];
            foreach ($widths as $w) {
                $url = $webp ? self::webp($path, $w) : self::thumb($path, $w);
                if ($url === null || isset($parts[$url])) {
                    continue;
                }
                $size        = self::size($url);
                $parts[$url] = $url . ' ' . ($size ? $size[0] : $w) . 'w';
            }

            return implode(', ', $parts);
        };
        $src  = self::thumb($path, 1280);
        $size = self::size($src);

        return [
            'phone'   => ['webp' => $set([412, 828], true), 'img' => $set([412, 828], false)],
            'desktop' => ['webp' => $set([1280, 1920], true), 'img' => $set([1280, 1920], false)],
            'src'     => $src,
            'width'   => $size[0] ?? null,
            'height'  => $size[1] ?? null,
        ];
    }

    /** thumb()/webp() core: the derivative URL, or null when only the original can be served. */
    private static function derive(string $path, int $width, int $quality, ?string $format): ?string
    {
        $rel      = ltrim($path, '/');
        $rel      = preg_replace('#^public/#', '', $rel);   // normalise to "uploads/..."

        try {
            $src = public_path($rel);
            if (!is_file($src)) {
                return null;
            }

            $ext = strtolower(pathinfo($rel, PATHINFO_EXTENSION));
            if (!in_array($ext, self::RASTER, true) || !extension_loaded('gd')) {
                return null;                                 // svg/gif/webp or no GD → leave alone
            }
            $out = $format ?? $ext;

            // Cache key changes when the source file or the requested size changes.
            $key      = md5($rel . '|' . filemtime($src) . '|' . $width . '|' . $quality . ($format ? '|' . $format : ''));
            $cacheRel = 'uploads/cache/' . $key . '.' . $out;
            $cacheAbs = public_path($cacheRel);

            if (is_file($cacheAbs)) {
                return asset('public/' . $cacheRel);
            }

            // Marker left when a derivative came out no smaller than the source: serve the
            // original without re-running GD on every page view.
            $skipAbs = $cacheAbs . '.skip';
            if (is_file($skipAbs)) {
                return null;
            }

            $dir = dirname($cacheAbs);
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
            if (!is_dir($dir) || !is_writable($dir)) {
                return null;                            // can't cache → serve original
            }

            if (!self::generate($src, $cacheAbs, $cacheRel, $ext, $width, $quality, $out)) {
                return null;
            }

            // Never serve a derivative that ended up larger than the source
            // (can happen re-encoding an already-optimised image with no downscale).
            if (@filesize($cacheAbs) >= @filesize($src)) {
                @unlink($cacheAbs);
                @touch($skipAbs);
                return null;
            }

            return asset('public/' . $cacheRel);
        } catch (\Throwable $e) {
            return null;                                // any failure → original, never break
        }
    }

    /**
     * srcset value built from thumb() at each width, e.g. "a.jpg 480w, b.jpg 800w".
     * Descriptors use the real pixel width of each file (thumb() never upscales), and a
     * URL that repeats (small source, or a pass-through original) is listed once.
     *
     * @param  string $path   Upload path as for thumb().
     * @param  int[]  $widths Requested widths, smallest first.
     */
    public static function srcset(string $path, array $widths): string
    {
        $parts = [];
        foreach ($widths as $width) {
            $url = self::thumb($path, $width);
            if (isset($parts[$url])) {
                continue;
            }
            $size        = self::size($url);
            $parts[$url] = $url . ' ' . ($size ? $size[0] : $width) . 'w';
        }

        return implode(', ', $parts);
    }

    /**
     * Pixel size of a local image given its public URL (as returned by thumb() or
     * asset('public/...')), for <img width height> so the layout does not jump while
     * images load. Returns null for remote URLs, missing files or non-images.
     *
     * @return array{0:int,1:int}|null
     */
    public static function size(?string $url): ?array
    {
        static $memo = [];
        if ($url === null || $url === '') {
            return null;
        }
        if (array_key_exists($url, $memo)) {
            return $memo[$url];
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $base = parse_url(url('/'), PHP_URL_PATH) ?: '';
        $rel  = ltrim(substr($path, strlen(rtrim($base, '/'))), '/');
        $rel  = rawurldecode(preg_replace('#^public/#', '', $rel));
        $file = public_path($rel);

        $info = (str_starts_with($path, rtrim($base, '/')) && is_file($file)) ? @getimagesize($file) : false;

        return $memo[$url] = ($info && $info[0] > 0 && $info[1] > 0) ? [$info[0], $info[1]] : null;
    }

    /** Create the resized derivative with GD. Returns true on success. */
    private static function generate(string $src, string $dest, string $cacheRel, string $ext, int $width, int $quality, ?string $out = null): bool
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

        if ($out === 'webp') {
            $ok = imagewebp($canvas, $dest, $quality);
        } elseif ($isPng) {
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
