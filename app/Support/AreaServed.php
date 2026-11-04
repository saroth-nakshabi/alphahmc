<?php

namespace App\Support;

/**
 * Turns the free-text areaServed of a service, category or package ("Abu Dhabi, Dubai, AE-AZ, AE-DU",
 * "UAE / GCC", a JSON list...) into schema.org places: one Country, City or Place per area, duplicates
 * and ISO codes folded into their names (Ahrefs audit, 4 Nov 2026).
 */
class AreaServed
{
    private const COUNTRIES = [
        'united arab emirates' => 'United Arab Emirates', 'uae' => 'United Arab Emirates', 'ae' => 'United Arab Emirates',
        'saudi arabia' => 'Saudi Arabia', 'ksa' => 'Saudi Arabia', 'oman' => 'Oman', 'qatar' => 'Qatar',
        'bahrain' => 'Bahrain', 'kuwait' => 'Kuwait', 'australia' => 'Australia', 'india' => 'India',
    ];

    private const CITIES = [
        'dubai' => 'Dubai', 'ae-du' => 'Dubai', 'abu dhabi' => 'Abu Dhabi', 'ae-az' => 'Abu Dhabi',
        'sharjah' => 'Sharjah', 'ae-sh' => 'Sharjah', 'ajman' => 'Ajman', 'ae-aj' => 'Ajman',
        'ras al khaimah' => 'Ras Al Khaimah', 'ae-rk' => 'Ras Al Khaimah', 'fujairah' => 'Fujairah', 'ae-fu' => 'Fujairah',
        'umm al quwain' => 'Umm Al Quwain', 'ae-uq' => 'Umm Al Quwain', 'al ain' => 'Al Ain',
    ];

    private const PLACES = ['gcc' => 'Gulf Cooperation Council', 'gulf cooperation council' => 'Gulf Cooperation Council'];

    /** @return array<int, array{'@type': string, name: string}> */
    public static function schema(mixed $value): array
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : $value;
        }
        $names = [];
        if (is_array($value)) {
            foreach ($value as $item) {
                $names[] = is_array($item) ? (string) ($item['name'] ?? '') : (string) $item;
            }
        } else {
            $names = preg_split('~[,;/()|]|\s+and\s+~i', (string) $value);
        }

        $out = [];
        foreach ($names as $name) {
            $name = trim($name, " \t\n\r\0\x0B\"'");
            if ($name === '') {
                continue;
            }
            $key = strtolower(preg_replace('/\s+/', ' ', $name));
            [$type, $label] = match (true) {
                isset(self::COUNTRIES[$key]) => ['Country', self::COUNTRIES[$key]],
                isset(self::CITIES[$key]) => ['City', self::CITIES[$key]],
                isset(self::PLACES[$key]) => ['Place', self::PLACES[$key]],
                default => ['Place', $name],
            };
            $out[$type . '|' . $label] = ['@type' => $type, 'name' => $label];
        }

        return array_values($out);
    }
}
