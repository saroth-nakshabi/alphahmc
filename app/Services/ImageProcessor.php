<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageProcessor
{
    // Quality 82 — recommended by Google PageSpeed; ~40-60% smaller than unoptimised JPEG.
    private const JPEG_QUALITY = 82;

    public static function saveAsJpeg(UploadedFile $file, string $directory, string $baseName): string
    {
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $fileName  = $baseName . '.jpg';
        $destPath  = rtrim($directory, '/') . '/' . $fileName;
        $mime      = $file->getMimeType();
        $src       = $file->getPathname();

        $image = match (true) {
            str_contains($mime, 'jpeg'), str_contains($mime, 'jpg') => imagecreatefromjpeg($src),
            str_contains($mime, 'png')  => self::pngOnWhite($src),
            str_contains($mime, 'gif')  => imagecreatefromgif($src),
            str_contains($mime, 'webp') => imagecreatefromwebp($src),
            default                     => self::guessImage($src),
        };

        if (!$image) {
            throw new \RuntimeException('ImageProcessor: could not decode image — unsupported format.');
        }

        imagejpeg($image, $destPath, self::JPEG_QUALITY);
        imagedestroy($image);

        return $fileName;
    }

    // Composite a transparent PNG onto a white canvas so the JPEG has no black areas.
    private static function pngOnWhite(string $src): \GdImage|false
    {
        $png = imagecreatefrompng($src);
        if (!$png) return false;

        $w      = imagesx($png);
        $h      = imagesy($png);
        $canvas = imagecreatetruecolor($w, $h);
        $white  = imagecolorallocate($canvas, 255, 255, 255);

        imagefilledrectangle($canvas, 0, 0, $w, $h, $white);
        imagealphablending($canvas, true);
        imagecopy($canvas, $png, 0, 0, 0, 0, $w, $h);
        imagedestroy($png);

        return $canvas;
    }

    // Last-resort: try each loader until one succeeds.
    private static function guessImage(string $src): \GdImage|false
    {
        return @imagecreatefromjpeg($src)
            ?: @imagecreatefromwebp($src)
            ?: self::pngOnWhite($src)
            ?: @imagecreatefromgif($src)
            ?: false;
    }
}
