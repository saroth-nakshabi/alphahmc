<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A team member with a bio page at /team/{key}: the experts who review service pages and guides
 * (brief "alphahmc-reviewers-and-linking", 30 Oct 2026; pages store the reviewer's key), and the blog
 * author (role "author", brief "alphahmc-blog-author"), who is not a reviewer.
 */
class Reviewer extends Model
{
    public const ROLE_REVIEWER = 'reviewer';
    public const ROLE_AUTHOR = 'author';

    protected $fillable = ['key', 'role', 'name', 'title', 'qualifications', 'experience', 'bio', 'reviews', 'linkedin', 'photo', 'sort_order'];

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

    /** The reviewers keyed by key, for the admin dropdowns and the "Reviewed by" line (the author is not one). */
    public static function byKey()
    {
        return static::team()->where('role', self::ROLE_REVIEWER);
    }

    /** Everyone with a bio page, keyed by key. */
    public static function team()
    {
        // once(): per request (and reset between tests), so a long-running worker never serves an old list.
        return once(fn () => static::orderBy('sort_order')->get()->keyBy('key'));
    }

    /** The team member whose name is a blog byline (author_name), or null for any other name. */
    public static function forAuthor(?string $name): ?self
    {
        $name = trim((string) $name);

        return $name === '' ? null : static::team()->first(fn ($m) => strcasecmp($m->name, $name) === 0);
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
