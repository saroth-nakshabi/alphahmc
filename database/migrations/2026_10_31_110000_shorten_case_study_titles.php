<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Shorter case study titles (brief "alphahmc-case-studies" part 1, Saroth 30 Oct 2026). The title is the H1,
 * the card title on the case study lists and the browser title ("<title> | Alpha Health Group").
 * URLs, descriptions and body text are unchanged. down() restores the titles as they were on the live site.
 */
return new class extends Migration
{
    /** slug => [title before, new title] */
    private const TITLES = [
        'royal-spanish-center' => ['Multi-Branch Ophthalmic Day Surgery & Primary Healthcare Centre: Engineering, Compliance & Management Outsourcing', 'Multi-Branch Eye Day Surgery Network'],
        'beijing-therapy'      => ['Multi-Branch Traditional Chinese Medicine Network: Regulatory, Licensing & Compliance Management', 'Multi-Branch TCM Network Licensing'],
        'revital-hospital'     => ['Specialised Rehabilitation Hospital: Engineering Approval, ADHICS & JAWDA Compliance Consultation', 'Rehabilitation Hospital: ADHICS & JAWDA'],
        'reem-hospital'        => ['Post-Operational Hospital Expansion & Engineering-Led Capacity Enhancement', 'Hospital Expansion & Capacity Upgrade'],
        'smch'                 => ['Hospital Compliance Structuring, DOH Approval & Quality Governance', 'Hospital DOH Approval & Quality Governance'],
        'healthcare-solutions' => ['Largest Medical Storage & Handling Facility in the Middle East', 'Medical Storage & Handling Facility'],
        'sanimed-group'        => ['Multi-Branch Full-Service General & Genetic Laboratory Network', 'General & Genetic Laboratory Network'],
        'ihlad-abu-dhabi'      => ['Integrated Wellness & Longevity Centre Development', 'Wellness & Longevity Centre Setup'],
        'dna-health-corp'      => ['Turnkey Wellness & Preventive Care Facility Development', 'Turnkey Wellness & Preventive Care Centre'],
        'broadway-vascular'    => ['Specialised State-of-the-Art Vascular Surgical Facility', 'Specialised Vascular Surgical Facility'],
        'vfs-global'           => ['Boutique High-Efficiency VIP Visa Medical Screening Facility', 'VIP Visa Medical Screening Facility'],
        'bone-care'            => ['License Reactivation & Multi-Specialty Surgical Expansion', 'License Reactivation & Surgical Expansion'],
        'nhs-medical-center'   => ['Visa & Occupational Screening Complex', 'Visa & Occupational Screening Complex'],
    ];

    public function up(): void
    {
        foreach (self::TITLES as $slug => [, $new]) {
            DB::table('projects')->where('slug', $slug)->update(['name' => $new]);
        }
    }

    public function down(): void
    {
        foreach (self::TITLES as $slug => [$old]) {
            DB::table('projects')->where('slug', $slug)->update(['name' => $old]);
        }
    }
};
