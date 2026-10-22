<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogRevision;
use App\Models\Scopes\PublishedScope;
use App\Models\User;
use App\Support\SiteCache;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

/**
 * Draft-safe blog API (brief alphahmc-blog-api.md, section 7). Runs against the MySQL test database
 * set in phpunit.xml (alphahmc_test), never the production database; each test is rolled back.
 */
class BlogDraftApiTest extends TestCase
{
    use DatabaseTransactions;

    private const TOKEN = 'test-token-0123456789abcdef';

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('alphahmc_test', DB::connection()->getDatabaseName(), 'Feature tests must run on alphahmc_test.');
        config(['services.agent_api.token_sha256' => hash('sha256', self::TOKEN)]);
        SiteCache::flush();
    }

    protected function tearDown(): void
    {
        SiteCache::flush();
        parent::tearDown();
    }

    /** API call as the agent sends it: Bearer token, JSON body, no Accept header. */
    private function api(string $method, string $uri, ?array $body = null, ?string $token = self::TOKEN)
    {
        $server = ['CONTENT_TYPE' => 'application/json'];
        if ($token !== null) {
            $server['HTTP_AUTHORIZATION'] = 'Bearer ' . $token;
        }

        return $this->call($method, $uri, [], [], [], $server, $body === null ? null : json_encode($body));
    }

    private function payload(string $slug = 'zz-test-draft', array $overrides = []): array
    {
        return array_merge([
            'slug'         => $slug,
            'site'         => 'alphahmc.com',
            'url'          => 'https://alphahmc.com/blog_single/' . $slug,
            'change'       => 'new',
            'title'        => 'Test Draft Title',
            'description'  => 'Test only.',
            'html'         => '<h1>Test Draft Title</h1><p>Hello world</p><script>x</script>',
            'sources'      => ['https://www.dha.gov.ae'],
            'reviewer'     => ['name' => '', 'credential' => 'Head of Content'],
            'lastReviewed' => null,
        ], $overrides);
    }

    private function draft(string $slug = 'zz-test-draft'): Blog
    {
        $this->api('POST', '/api/agent/blogs', $this->payload($slug))->assertStatus(201);

        return Blog::withoutGlobalScope(PublishedScope::class)->where('slug', $slug)->firstOrFail();
    }

    private function publishedPost(string $slug = 'zz-live-post'): Blog
    {
        $blog = Blog::create([
            'title' => 'Live Title', 'slug' => $slug, 'description' => 'Live description.',
            'content' => '<p>Live body text</p>', 'image' => 'live.jpg', 'published_date' => '2026-01-10',
        ]);

        return $blog->fresh();
    }

    private function admin(array $permissions = ['view blogs', 'edit blogs']): User
    {
        $user = User::forceCreate([
            'first_name' => 'Test', 'last_name' => 'Admin', 'email' => 'test-admin-' . uniqid() . '@example.com',
            'phone' => '+9715' . random_int(10000000, 99999999), 'password' => Hash::make('secret-password'),
        ]);
        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'web');
        }
        $user->givePermissionTo($permissions);
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $user;
    }

    private function readyToPublish(Blog $blog): void
    {
        $blog->forceFill(['image' => 'cover.jpg', 'reviewer_name' => 'Dr Test Reviewer', 'last_reviewed' => '2026-10-20'])->save();
    }

    public function test_token_is_required(): void
    {
        $this->api('GET', '/api/agent/blogs/anything', null, null)->assertStatus(401)->assertExactJson(['message' => 'Unauthenticated.']);
        $this->api('GET', '/api/agent/blogs/anything', null, 'wrong-token')->assertStatus(401);

        config(['services.agent_api.token_sha256' => '']);
        $this->api('GET', '/api/agent/blogs/anything')->assertStatus(401);
    }

    public function test_draft_create_returns_a_signed_preview_url(): void
    {
        $res = $this->api('POST', '/api/agent/blogs', $this->payload())->assertStatus(201)
            ->assertJson(['slug' => 'zz-test-draft', 'status' => 'draft']);

        $url = $res->json('previewUrl');
        $this->assertStringContainsString('/blog-preview/', $url);
        $this->assertStringContainsString('signature=', $url);

        $blog = Blog::withoutGlobalScope(PublishedScope::class)->where('slug', 'zz-test-draft')->first();
        $this->assertSame('draft', $blog->status);
        $this->assertNull($blog->published_date);
        $this->assertStringNotContainsString('<script', $blog->content);
        $this->assertStringNotContainsString('<h1>', $blog->content);
        $this->assertNotNull($blog->agent_written_at);
        $this->assertNull($blog->admin_edited_at);
    }

    public function test_draft_is_not_public_anywhere(): void
    {
        $this->draft();

        $this->get('/blog_single/zz-test-draft')->assertStatus(404);
        // The slug only appears in links to the post (the search page echoes the query, so not the title).
        foreach (['/healthcare-management-update-insights', '/ahg-updates', '/blog', '/', '/search?s=Test+Draft+Title'] as $page) {
            $this->assertStringNotContainsString('zz-test-draft', $this->get($page)->getContent(), "Draft leaked on {$page}");
        }
        $this->assertStringNotContainsString('zz-test-draft', $this->get('/search/live?s=Test+Draft')->getContent());
        $this->assertStringNotContainsString('zz-test-draft', $this->get('/sitemap.xml')->getContent());
        $this->assertSame(0, Blog::where('slug', 'zz-test-draft')->count());
    }

    public function test_preview_works_and_is_noindex_tamper_and_expiry_fail(): void
    {
        $url = $this->api('POST', '/api/agent/blogs', $this->payload())->json('previewUrl');

        $res = $this->get($url)->assertStatus(200)->assertSee('Test Draft Title')->assertSee('Preview, not published');
        $this->assertStringContainsString('content="noindex, nofollow"', $res->getContent());
        $this->assertSame('noindex, nofollow', $res->headers->get('X-Robots-Tag'));

        $this->get(preg_replace('/signature=([0-9a-f])/', 'signature=' . 'x', $url))->assertStatus(403);

        $this->travel(15)->days();
        $this->get($url)->assertStatus(403);
    }

    public function test_only_admin_publish_makes_a_draft_public(): void
    {
        $blog = $this->draft();
        $this->readyToPublish($blog);

        $this->actingAs($this->admin(['view blogs']))->post('/manage-blog/publish/' . $blog->id)->assertStatus(403);
        $this->assertSame('draft', $blog->fresh()->status);

        $this->actingAs($this->admin())->post('/manage-blog/publish/' . $blog->id)->assertStatus(200);
        $fresh = $blog->fresh();
        $this->assertSame('published', $fresh->status);
        $this->assertSame(now()->toDateString(), $fresh->published_date->toDateString());
        $this->get('/blog_single/zz-test-draft')->assertStatus(200);
        $this->assertStringContainsString('zz-test-draft', $this->get('/sitemap.xml')->getContent());

        $this->actingAs($this->admin())->post('/manage-blog/publish/' . $blog->id)->assertStatus(409);
    }

    public function test_publish_guards(): void
    {
        $admin = $this->admin();
        $cases = [
            'confirm marker' => ['content' => '<p>Fee AED 500 [confirm]</p>'],
            'no image'       => ['image' => null],
            'no reviewer'    => ['reviewer_name' => null],
            'no review date' => ['last_reviewed' => null],
        ];
        foreach ($cases as $label => $change) {
            $blog = $this->draft('zz-guard-' . substr(md5($label), 0, 6));
            $this->readyToPublish($blog);
            $blog->forceFill($change)->save();

            $this->actingAs($admin)->post('/manage-blog/publish/' . $blog->id)->assertStatus(422);
            $this->assertSame('draft', $blog->fresh()->status, $label);
        }
    }

    public function test_token_cannot_publish_or_delete(): void
    {
        $blog = $this->draft();

        $this->assertContains($this->api('DELETE', '/api/agent/blogs/zz-test-draft')->status(), [404, 405]);
        $this->assertContains($this->api('POST', '/api/agent/blogs/zz-test-draft/publish')->status(), [404, 405]);
        $this->assertContains($this->api('POST', '/manage-blog/publish/' . $blog->id)->status(), [302, 401, 419]);

        $this->assertSame('draft', $blog->fresh()->status);
    }

    public function test_update_keeps_the_slug(): void
    {
        $blog = $this->draft();

        $this->api('PUT', '/api/agent/blogs/zz-test-draft', $this->payload('zz-other-slug'))->assertStatus(422);

        $this->api('PUT', '/api/agent/blogs/zz-test-draft', $this->payload('zz-test-draft', ['title' => 'Changed Title', 'html' => '<p>New body</p>']))
            ->assertStatus(200)->assertJson(['status' => 'draft']);
        $fresh = Blog::withoutGlobalScope(PublishedScope::class)->find($blog->id);
        $this->assertSame('zz-test-draft', $fresh->slug);
        $this->assertSame('Changed Title', $fresh->title);
        $this->assertSame('<p>New body</p>', $fresh->content);
    }

    public function test_reviewer_fields_survive_an_empty_repush(): void
    {
        $this->api('POST', '/api/agent/blogs', $this->payload('zz-test-draft', ['reviewer' => ['name' => 'Dr Agent Set', 'credential' => 'MD']]))->assertStatus(201);

        $this->api('PUT', '/api/agent/blogs/zz-test-draft', $this->payload('zz-test-draft', ['reviewer' => ['name' => '', 'credential' => '']]))->assertStatus(200);

        $fresh = Blog::withoutGlobalScope(PublishedScope::class)->where('slug', 'zz-test-draft')->first();
        $this->assertSame('Dr Agent Set', $fresh->reviewer_name);
        $this->assertSame('MD', $fresh->reviewer_credential);
    }

    public function test_revision_does_not_change_the_live_post(): void
    {
        $live = $this->publishedPost();
        DB::table('blogs')->where('id', $live->id)->update(['updated_at' => '2026-01-10 08:00:00']);

        $res = $this->api('PUT', '/api/agent/blogs/zz-live-post', $this->payload('zz-live-post', ['change' => 'edit', 'title' => 'Rewritten Title', 'html' => '<p>Rewritten body</p>']))
            ->assertStatus(200)->assertJson(['status' => 'revision']);

        $page = $this->get('/blog_single/zz-live-post')->assertStatus(200);
        $page->assertSee('Live Title')->assertDontSee('Rewritten Title');
        $this->assertSame('2026-01-10 08:00:00', DB::table('blogs')->where('id', $live->id)->value('updated_at'));

        $this->api('PUT', '/api/agent/blogs/zz-live-post', $this->payload('zz-live-post', ['title' => 'Rewritten Title 2', 'html' => '<p>Rewritten body 2</p>']))->assertStatus(200);
        $this->assertSame(1, BlogRevision::where('blog_id', $live->id)->count());

        $this->get($res->json('previewUrl'))->assertStatus(200)->assertSee('Rewritten Title 2');

        $rev = BlogRevision::where('blog_id', $live->id)->first();
        $rev->forceFill(['reviewer_name' => 'Dr Rev', 'last_reviewed' => '2026-10-20'])->save();
        $this->actingAs($this->admin())->post('/manage-blog/revision/' . $live->id . '/publish')->assertStatus(200);

        $fresh = $live->fresh();
        $this->assertSame('Rewritten Title 2', $fresh->title);
        $this->assertSame('<p>Rewritten body 2</p>', $fresh->content);
        $this->assertSame('zz-live-post', $fresh->slug);
        $this->assertSame('2026-01-10', $fresh->published_date->toDateString());
        $this->assertSame(now()->toDateString(), $fresh->updated_date->toDateString());
        $this->assertSame(0, BlogRevision::where('blog_id', $live->id)->count());
        $this->get('/blog_single/zz-live-post')->assertSee('Rewritten Title 2');
    }

    public function test_publish_revision_checks(): void
    {
        $live = $this->publishedPost();
        $this->api('PUT', '/api/agent/blogs/zz-live-post', $this->payload('zz-live-post', ['html' => '<p>Text [confirm]</p>']))->assertStatus(200);
        $rev = BlogRevision::where('blog_id', $live->id)->first();
        $rev->forceFill(['reviewer_name' => 'Dr Rev', 'last_reviewed' => '2026-10-20'])->save();
        $admin = $this->admin();

        $this->actingAs($admin)->post('/manage-blog/revision/' . $live->id . '/publish')->assertStatus(422);
        $rev->forceFill(['content' => '<p>Clean text</p>', 'reviewer_name' => null])->save();
        $this->actingAs($admin)->post('/manage-blog/revision/' . $live->id . '/publish')->assertStatus(422);
        $rev->forceFill(['reviewer_name' => 'Dr Rev', 'last_reviewed' => null])->save();
        $this->actingAs($admin)->post('/manage-blog/revision/' . $live->id . '/publish')->assertStatus(422);

        // No new cover image is needed for a revision (the live post has one).
        $rev->forceFill(['last_reviewed' => '2026-10-20'])->save();
        $this->actingAs($admin)->post('/manage-blog/revision/' . $live->id . '/publish')->assertStatus(200);
    }

    public function test_edited_by_person_blocks_the_agent(): void
    {
        $blog = $this->draft();
        $this->travel(1)->minutes();
        $this->actingAs($this->admin())->post('/manage-blog/update/' . $blog->id, [
            'title' => 'Person Title', 'slug' => 'zz-test-draft', 'content' => '<p>Person body</p>', 'description' => 'Person desc',
        ])->assertStatus(201);
        $before = Blog::withoutGlobalScope(PublishedScope::class)->find($blog->id);

        $this->travel(1)->minutes();
        $this->api('PUT', '/api/agent/blogs/zz-test-draft', $this->payload('zz-test-draft', ['title' => 'Agent Title']))
            ->assertStatus(409)->assertJson(['error' => 'edited_by_person']);
        $this->api('PUT', '/api/agent/blogs/zz-test-draft?force=1', $this->payload('zz-test-draft', ['title' => 'Agent Title']))->assertStatus(409);

        $after = Blog::withoutGlobalScope(PublishedScope::class)->find($blog->id);
        $this->assertSame('Person Title', $after->title);
        $this->assertSame('<p>Person body</p>', $after->content);
        $this->assertEquals($before->agent_written_at, $after->agent_written_at);

        // Same rule for a pending revision; a null admin_edited_at never blocks.
        $live = $this->publishedPost();
        $this->api('PUT', '/api/agent/blogs/zz-live-post', $this->payload('zz-live-post'))->assertStatus(200);
        $this->api('PUT', '/api/agent/blogs/zz-live-post', $this->payload('zz-live-post'))->assertStatus(200);
        $this->travel(1)->minutes();
        BlogRevision::where('blog_id', $live->id)->first()->forceFill(['admin_edited_at' => now()])->save();
        $this->travel(1)->minutes();
        $this->api('PUT', '/api/agent/blogs/zz-live-post', $this->payload('zz-live-post'))->assertStatus(409)->assertJson(['error' => 'edited_by_person']);
    }

    public function test_unknown_slug_without_accept_header_is_json_404(): void
    {
        $res = $this->api('GET', '/api/agent/blogs/nope-not-here')->assertStatus(404);
        $this->assertSame(['message' => 'Not found.'], $res->json());
    }

    public function test_get_reports_state_and_preview(): void
    {
        $this->draft();
        $this->api('GET', '/api/agent/blogs/zz-test-draft')->assertStatus(200)
            ->assertJson(['slug' => 'zz-test-draft', 'status' => 'draft', 'hasPendingRevision' => false])
            ->assertJsonPath('previewUrl', fn ($u) => str_contains((string) $u, 'signature='));

        $this->publishedPost();
        $this->api('GET', '/api/agent/blogs/zz-live-post')->assertStatus(200)->assertJson(['status' => 'published', 'previewUrl' => null]);
        $this->api('POST', '/api/agent/blogs', $this->payload('zz-live-post'))->assertStatus(409);
    }

    public function test_reviewer_url_must_be_http(): void
    {
        $this->api('POST', '/api/agent/blogs', $this->payload('zz-test-draft', ['reviewer' => ['name' => 'A', 'url' => 'javascript:x']]))->assertStatus(422);

        $blog = $this->draft();
        $this->actingAs($this->admin())->post('/manage-blog/update/' . $blog->id, [
            'title' => 'T', 'slug' => 'zz-test-draft', 'content' => '<p>c</p>', 'reviewer_url' => 'javascript:x',
        ], ['Accept' => 'application/json'])->assertStatus(422);

        // A bad stored value is never rendered.
        $live = $this->publishedPost();
        $live->forceFill(['reviewer_name' => 'Dr Bad Link', 'last_reviewed' => '2026-10-20', 'reviewer_url' => 'javascript:alert(1)'])->save();
        $html = $this->get('/blog_single/zz-live-post')->getContent();
        $this->assertStringContainsString('Dr Bad Link', $html);
        $this->assertStringNotContainsString('javascript:alert(1)', $html);
    }

    public function test_page_output_with_and_without_reviewer(): void
    {
        $live = $this->publishedPost();
        $live->forceFill([
            'reviewer_name' => 'Dr Jane Reviewer', 'reviewer_credential' => 'Head of Licensing', 'reviewer_url' => 'https://example.com/jane',
            'last_reviewed' => '2026-10-20', 'sources' => ['https://www.dha.gov.ae/en/page'], 'updated_date' => '2026-10-21',
        ])->save();

        $html = $this->get('/blog_single/zz-live-post')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Reviewed by', $html);
        $this->assertStringContainsString('Dr Jane Reviewer', $html);
        $this->assertStringContainsString('dha.gov.ae</a>', $html);
        preg_match('/<script type="application\/ld\+json">(\{"@context":"https:\/\/schema.org","@type":"BlogPosting".*?)<\/script>/s', $html, $m);
        $ld = json_decode($m[1] ?? '{}', true);
        $this->assertSame('Dr Jane Reviewer', $ld['reviewedBy']['name'] ?? null);
        $this->assertSame(['https://www.dha.gov.ae/en/page'], $ld['citation'] ?? null);
        $this->assertArrayHasKey('author', $ld);
        $this->assertStringStartsWith('2026-01-10', $ld['datePublished'] ?? '');
        $this->assertStringStartsWith('2026-10-21', $ld['dateModified'] ?? '');

        $plain = $this->publishedPost('zz-plain-post');
        $html = $this->get('/blog_single/zz-plain-post')->getContent();
        $this->assertStringNotContainsString('Reviewed by', $html);
        $this->assertStringNotContainsString('>Sources<', $html);
    }

    public function test_sitemap_excludes_drafts_and_uses_updated_date(): void
    {
        $this->draft();
        $live = $this->publishedPost();
        $live->forceFill(['updated_date' => '2026-09-15'])->save();

        $xml = $this->get('/sitemap.xml')->getContent();
        $this->assertStringNotContainsString('zz-test-draft', $xml);
        $this->assertMatchesRegularExpression('#zz-live-post</loc>\s*<lastmod>2026-09-15#', $xml);
    }

    public function test_existing_rows_default_to_published(): void
    {
        DB::table('blogs')->insert(['title' => 'Raw', 'slug' => 'zz-raw-row', 'content' => 'x', 'description' => 'x', 'created_at' => now(), 'updated_at' => now()]);

        $this->assertSame('published', DB::table('blogs')->where('slug', 'zz-raw-row')->value('status'));
        $this->assertSame(0, DB::table('blogs')->where('status', '!=', 'published')->where('slug', 'not like', 'zz-%')->count());
    }

    public function test_admin_get_requires_view_blogs(): void
    {
        $blog = $this->draft();

        $this->actingAs($this->admin([]))->post('/manage-blog/get', ['id' => $blog->id])->assertStatus(403);
        $this->actingAs($this->admin(['view blogs']))->post('/manage-blog/get', ['id' => $blog->id])->assertStatus(201)
            ->assertJsonPath('data.status', 'draft');
    }

    public function test_admin_list_shows_drafts_revisions_and_actions(): void
    {
        $this->draft();
        $live = $this->publishedPost();
        $this->api('PUT', '/api/agent/blogs/zz-live-post', $this->payload('zz-live-post'))->assertStatus(200);

        $html = $this->actingAs($this->admin())->get('/manage-blog')->assertStatus(200)->getContent();
        $this->assertStringContainsString('Test Draft Title', $html);
        $this->assertStringContainsString('>Draft</span>', $html);
        $this->assertStringContainsString('Revision pending', $html);
        $this->assertStringContainsString('/manage-blog/publish/', $html);
        $this->assertStringContainsString('/manage-blog/revision/' . $live->id . '/publish', $html);
        $this->assertStringContainsString('/blog-preview/', $html);
        $this->assertMatchesRegularExpression('/Drafts \(\d+\)/', $html);
    }

    public function test_validation_answers_json_without_accept(): void
    {
        foreach ([
            ['title' => null],
            ['slug' => 'Bad Slug'],
            ['lastReviewed' => '31/12/2026'],
            ['sources' => ['ftp://example.com/x']],
        ] as $override) {
            $res = $this->api('POST', '/api/agent/blogs', array_merge($this->payload(), $override));
            $res->assertStatus(422);
            $this->assertIsArray($res->json('errors'));
        }
    }

    public function test_rate_limit(): void
    {
        for ($i = 1; $i <= 30; $i++) {
            $this->api('GET', '/api/agent/blogs/nope-not-here')->assertStatus(404);
        }
        $res = $this->api('GET', '/api/agent/blogs/nope-not-here')->assertStatus(429);
        $this->assertNotNull($res->headers->get('Retry-After'));
    }
}
