<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Reviewer;
use App\Support\InternalLinks;
use App\Support\SiteCache;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Reviewer bio pages, the "Reviewed by" line and the lazy menu rows (brief alphahmc-reviewers-and-linking).
 * Runs against alphahmc_test (phpunit.xml); each test is rolled back.
 */
class ReviewerPagesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('alphahmc_test', DB::connection()->getDatabaseName(), 'Feature tests must run on alphahmc_test.');
        SiteCache::flush();
    }

    protected function tearDown(): void
    {
        SiteCache::flush();
        parent::tearDown();
    }

    private function reviewer(): Reviewer
    {
        return Reviewer::updateOrCreate(['key' => 'test-reviewer'], [
            'name' => 'Dr Test Person', 'title' => 'Quality Consultant', 'qualifications' => 'CPHQ.',
            'experience' => '10+ years.', 'bio' => 'Reviews quality pages.', 'reviews' => 'quality, audits.',
            'linkedin' => 'https://www.linkedin.com/in/test-person/', 'sort_order' => 99,
        ]);
    }

    public function test_bio_page_has_profile_schema_and_no_image_until_a_photo_exists(): void
    {
        $r = $this->reviewer();

        $res = $this->get('/team/' . $r->key)->assertOk()
            ->assertSee('<h1>Dr Test Person</h1>', false)
            ->assertSee('"@type":"ProfilePage"', false)
            ->assertSee('"sameAs":["https://www.linkedin.com/in/test-person/"]', false)
            ->assertSee('/#organization', false)
            ->assertSee('name="robots" content="index, follow"', false);

        $this->assertStringNotContainsString('"image"', $res->getContent());
        $this->assertStringContainsString('>TP</span>', $res->getContent());   // initials, no "Dr"
        $this->get('/team/nobody-here')->assertNotFound();
    }

    public function test_reviewed_by_needs_both_a_reviewer_and_a_date(): void
    {
        $r = $this->reviewer();
        $blog = new Blog(['title' => 'T', 'slug' => 'reviewer-test']);
        $blog->reviewer = $r->key;

        $this->assertNull(Reviewer::forPage($blog));       // no date yet: nothing shows
        $blog->reviewed_at = '2026-10-27';
        $this->assertSame($r->key, Reviewer::forPage($blog)?->key);
    }

    public function test_blog_shows_reviewed_by_line_with_schema(): void
    {
        $r = $this->reviewer();
        $blog = new Blog(['title' => 'Reviewer line test', 'slug' => 'reviewer-line-test', 'content' => '<p>Body.</p>', 'description' => 'Test.', 'image' => 'test.jpg',
                          'published_date' => '2026-10-01']);
        $blog->forceFill(['status' => 'published', 'reviewer' => $r->key, 'reviewed_at' => '2026-10-27'])->saveQuietly();

        $this->get('/blog_single/' . $blog->slug)->assertOk()
            ->assertSee('Reviewed by <a href="' . $r->url() . '">Dr Test Person</a>', false)
            ->assertSee('27 October 2026')
            ->assertSee('"lastReviewed":"2026-10-27"', false);
    }

    public function test_menu_rows_are_not_in_the_page_but_served_separately(): void
    {
        $home = $this->get('/')->assertOk()->getContent();
        $this->assertStringNotContainsString('class="service-item', $home);
        $this->assertStringContainsString('data-src="' . route('nav.items') . '"', $home);

        $this->get('/nav-items')->assertOk()->assertHeader('X-Robots-Tag', 'noindex');
    }

    public function test_case_studies_link_from_their_facility_category(): void
    {
        $links = InternalLinks::forPage('/service-category/day-surgery-centers');
        $urls = array_column($links, 'url');
        foreach (['broadway-vascular', 'bone-care', 'royal-spanish-center'] as $slug) {
            if (DB::table('projects')->where('slug', $slug)->exists()) {
                $this->assertContains(route('front.project_details', $slug), $urls);
            }
        }
        $this->assertSame([], InternalLinks::forPage('/service-category/not-a-mapped-category'));
    }
}
