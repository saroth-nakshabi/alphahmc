<?php

namespace Tests\Unit;

use App\Support\AreaServed;
use PHPUnit\Framework\TestCase;

class AreaServedTest extends TestCase
{
    public function test_country_and_iso_code_fold_into_one_country(): void
    {
        $this->assertSame([['@type' => 'Country', 'name' => 'United Arab Emirates']], AreaServed::schema('United Arab Emirates, AE'));
    }

    public function test_cities_with_codes_are_deduplicated(): void
    {
        $this->assertSame(
            [['@type' => 'City', 'name' => 'Abu Dhabi'], ['@type' => 'City', 'name' => 'Dubai']],
            AreaServed::schema('Abu Dhabi, Dubai,  AE-AZ, AE-DU')
        );
    }

    public function test_slash_and_brackets_split(): void
    {
        $this->assertSame(
            [['@type' => 'Country', 'name' => 'United Arab Emirates'], ['@type' => 'Place', 'name' => 'Gulf Cooperation Council']],
            AreaServed::schema('UAE / GCC')
        );
        $this->assertSame(
            [['@type' => 'Country', 'name' => 'United Arab Emirates'], ['@type' => 'City', 'name' => 'Dubai'], ['@type' => 'City', 'name' => 'Abu Dhabi']],
            AreaServed::schema('UAE (Dubai, Abu Dhabi)')
        );
    }

    public function test_quoted_list_and_json(): void
    {
        $this->assertSame(
            [['@type' => 'Country', 'name' => 'United Arab Emirates'], ['@type' => 'City', 'name' => 'Sharjah'], ['@type' => 'Country', 'name' => 'Saudi Arabia']],
            AreaServed::schema('"UAE",   "Sharjah",  "Saudi Arabia",')
        );
        $this->assertSame([['@type' => 'City', 'name' => 'Dubai']], AreaServed::schema('[{"type":"City","name":"Dubai"}]'));
    }

    public function test_unknown_name_is_a_place_and_empty_is_empty(): void
    {
        $this->assertSame([['@type' => 'Place', 'name' => 'Northern Emirates']], AreaServed::schema('Northern Emirates'));
        $this->assertSame([], AreaServed::schema(null));
        $this->assertSame([], AreaServed::schema(''));
    }
}
