<?php

namespace Tests\Unit;

use App\Support\Related;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RelatedTitleMatchTest extends TestCase
{
    /** Real licensing article titles (blogs 21, 22, 23, 38, 68, 71). */
    private function licensingArticles(): array
    {
        return [
            21 => 'How to Get Your Healthcare Professional License in Saudi Arabia: A Complete Guide',
            22 => 'How to Obtain Your Healthcare Professional License in Qatar: A Practitioner Guide',
            23 => 'How to Obtain a Healthcare Professional License in Oman: A Practitioner Guide',
            38 => 'How to Get a Healthcare Professional License in the UAE: A Complete Regulatory Guide',
            68 => 'How to Get Your UAE Healthcare License in 2026: The Complete Guide for Professionals',
            71 => 'How to Transfer Your Healthcare License Between UAE Authorities Without Losing Time',
        ];
    }

    #[DataProvider('servicePages')]
    public function test_picks_the_article_that_matches_the_service(string $serviceText, int $expected): void
    {
        $this->assertSame($expected, Related::bestTitleMatch($serviceText, $this->licensingArticles()));
    }

    public static function servicePages(): array
    {
        return [
            'UAE'      => ['Healthcare Professional Licensing UAE Healthcare Professional Licensing UAE | DHA, DOH, MOHAP', 38],
            'transfer' => ['Healthcare Professional License Transfer UAE Healthcare License Transfer | DHA, DOH, MOHAP & SHA', 71],
            'Saudi'    => ['Saudi Healthcare Professional Licensing Saudi Healthcare Professional Licensing | SCFHS Registration', 21],
            'Qatar'    => ['Healthcare Professional Licensing Qatar Healthcare Professional Licensing Qatar | MOPH DHP Licensing', 22],
            'Oman'     => ['Healthcare Professional Licensing in Oman Healthcare Professional Licensing in Oman | Medical License', 23],
        ];
    }

    public function test_returns_null_without_candidates_or_overlap(): void
    {
        $this->assertNull(Related::bestTitleMatch('Clinic setup', []));
        $this->assertNull(Related::bestTitleMatch('Clinic setup', [1 => 'Jawda quality indicators explained']));
    }

    public function test_ties_go_to_the_first_candidate_in_given_order(): void
    {
        $this->assertSame(5, Related::bestTitleMatch('ADHICS audit', [5 => 'ADHICS audit guide', 9 => 'ADHICS audit checklist']));
    }
}
