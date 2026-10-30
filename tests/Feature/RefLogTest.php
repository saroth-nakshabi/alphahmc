<?php

namespace Tests\Feature;

use App\Models\RefClick;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * POST /api/ref-log (brief "alphahmc-ref-click-log"). Runs against alphahmc_test; each test is rolled back.
 */
class RefLogTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assertSame('alphahmc_test', DB::connection()->getDatabaseName(), 'Feature tests must run on alphahmc_test.');
    }

    private function logRef(array $body, ?string $origin = null)
    {
        return $this->withHeaders(['Origin' => $origin ?? config('app.url')])->postJson('/api/ref-log', $body);
    }

    public function test_first_tap_stores_the_click_and_later_taps_only_move_last_seen(): void
    {
        $this->logRef(['ref' => 'AHA-TEST01', 'gclid' => 'TEST-REF-1', 'utm_source' => 'google', 'utm_medium' => 'cpc',
            'landing_path' => '/services/x?gclid=TEST-REF-1', 'click_at' => now()->subMinutes(5)->getTimestampMs(),
            'ad_consent' => 'not_required'])->assertNoContent();

        $row = RefClick::where('ref', 'AHA-TEST01')->firstOrFail();
        $this->assertSame('google_ads', $row->channel);
        $this->assertSame('TEST-REF-1', $row->gclid);
        $this->assertSame('/services/x', $row->landing_path);   // path only
        $this->assertNotNull($row->click_at);

        $this->travel(2)->minutes();
        $this->logRef(['ref' => 'AHA-TEST01', 'gclid' => '', 'utm_source' => 'other', 'ad_consent' => 'not_required'])->assertNoContent();

        $again = RefClick::where('ref', 'AHA-TEST01')->get();
        $this->assertCount(1, $again);
        $this->assertSame('TEST-REF-1', $again[0]->gclid);        // never replaced by an empty one
        $this->assertSame('google', $again[0]->utm_source);
        $this->assertTrue($again[0]->last_seen_at->gt($row->last_seen_at));
    }

    public function test_denied_consent_keeps_the_row_without_click_fields(): void
    {
        $this->logRef(['ref' => 'AHA-TEST02', 'gclid' => 'SHOULD-NOT-STORE', 'click_at' => now()->getTimestampMs(),
            'ad_consent' => 'denied'])->assertNoContent();

        $row = RefClick::where('ref', 'AHA-TEST02')->firstOrFail();
        $this->assertNull($row->gclid);
        $this->assertNull($row->click_at);
        $this->assertSame('denied', $row->ad_consent);
    }

    public function test_malformed_ref_is_rejected_and_nothing_is_written(): void
    {
        $before = RefClick::count();
        $this->logRef(['ref' => 'AHX-12', 'ad_consent' => 'granted'])->assertStatus(422);
        $this->logRef(['ref' => "AHA-TEST03'; DROP", 'ad_consent' => 'granted'])->assertStatus(422);
        $this->assertSame($before, RefClick::count());
    }

    public function test_other_sites_cannot_write(): void
    {
        $this->logRef(['ref' => 'AHA-TEST04', 'ad_consent' => 'granted'], 'https://evil.example')->assertForbidden();
        $this->assertFalse(RefClick::where('ref', 'AHA-TEST04')->exists());
    }

    public function test_rows_older_than_120_days_are_pruned(): void
    {
        RefClick::create(['ref' => 'AHD-OLD001', 'channel' => 'direct', 'ad_consent' => 'not_required',
            'first_seen_at' => now()->subDays(130), 'last_seen_at' => now()->subDays(121)]);
        RefClick::create(['ref' => 'AHD-NEW001', 'channel' => 'direct', 'ad_consent' => 'not_required',
            'first_seen_at' => now()->subDays(10), 'last_seen_at' => now()->subDays(10)]);

        RefClick::prune();

        $this->assertFalse(RefClick::where('ref', 'AHD-OLD001')->exists());
        $this->assertTrue(RefClick::where('ref', 'AHD-NEW001')->exists());
    }
}
