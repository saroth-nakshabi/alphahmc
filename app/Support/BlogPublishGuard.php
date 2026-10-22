<?php

namespace App\Support;

use Illuminate\Support\Facades\Validator;

/**
 * Checks a person must finish before a draft or a staged revision goes live (brief 5.2). One rule
 * set for Publish and Publish revision; the cover image is only required for a new post.
 */
class BlogPublishGuard
{
    /** @return string|null The first problem in plain words, or null when it may be published. */
    public static function problem(?string $title, ?string $content, ?string $reviewerName, $lastReviewed, ?string $image, bool $requireImage): ?string
    {
        return match (true) {
            trim((string) $title) === ''                => 'Add a title before publishing.',
            trim(strip_tags((string) $content)) === ''  => 'Add the article text before publishing.',
            str_contains((string) $content, '[confirm]') => 'Resolve every [confirm] marker in the text before publishing.',
            $requireImage && trim((string) $image) === '' => 'Upload a cover image before publishing.',
            trim((string) $reviewerName) === ''         => 'Add the reviewer name before publishing.',
            empty($lastReviewed)                        => 'Add the last reviewed date before publishing.',
            default                                     => null,
        };
    }

    /** reviewer_url is shown only when it is a valid http(s) URL, re-checked at render time. */
    public static function safeUrl(?string $url): ?string
    {
        if ($url === null || trim($url) === '') {
            return null;
        }

        return Validator::make(['u' => $url], ['u' => 'url:http,https|max:2048'])->passes() ? $url : null;
    }
}
