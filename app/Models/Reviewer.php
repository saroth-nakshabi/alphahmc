<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A named expert who reviews service pages and guides; bio page at /team/{key}
 * (brief "alphahmc-reviewers-and-linking", 30 Oct 2026). Pages store the reviewer's key.
 */
class Reviewer extends Model
{
    protected $fillable = ['key', 'name', 'title', 'qualifications', 'experience', 'bio', 'reviews', 'linkedin', 'photo', 'sort_order'];

    public function getRouteKeyName(): string
    {
        return 'key';
    }

    public function url(): string
    {
        return route('front.reviewer', $this->key);
    }

    /** Schema @id of the Person on the bio page. */
    public function personId(): string
    {
        return $this->url() . '#person';
    }

    public function initials(): string
    {
        $words = preg_split('/\s+/', preg_replace('/^(Dr|Eng)\.?\s+/i', '', trim($this->name)));

        return strtoupper(mb_substr($words[0] ?? '', 0, 1) . mb_substr(end($words) ?: '', 0, 1));
    }

    /** All reviewers keyed by key, for the admin dropdowns and the "Reviewed by" line. */
    public static function byKey()
    {
        // once(): per request (and reset between tests), so a long-running worker never serves an old list.
        return once(fn () => static::orderBy('sort_order')->get()->keyBy('key'));
    }

    /**
     * Reviewer of a page when its review date is set, else null: the "Reviewed by" line and the
     * schema show only after the reviewer has read the page.
     */
    public static function forPage($page): ?self
    {
        if (!$page || empty($page->reviewer) || empty($page->reviewed_at)) {
            return null;
        }

        return static::byKey()->get($page->reviewer);
    }
}
