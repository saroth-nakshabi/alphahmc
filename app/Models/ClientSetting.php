<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Singleton settings for the public "Our Clients" page hero
 * (background image + heading/intro content), managed from the
 * dashboard Clients page.
 */
class ClientSetting extends Model
{
    protected $fillable = [
        'hero_image',
        'hero_eyebrow',
        'hero_title',
        'hero_subtitle',
        'hero_description',
    ];

    public static function current(): self
    {
        return self::firstOrCreate([], [
            'hero_eyebrow'     => 'Trusted Partnerships',
            'hero_title'       => 'Healthcare Facilities',
            'hero_subtitle'    => 'That Trust Alpha',
            'hero_description' => 'From DOH-licensed hospitals to homecare providers and specialty clinics, Alpha Health Group partners with leading healthcare organisations across the UAE to deliver compliance, accreditation, and operational excellence.',
        ]);
    }
}
