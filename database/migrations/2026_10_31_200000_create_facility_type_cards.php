<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * "Set up any type of healthcare facility" cards on /service-category/facility-licensing-setup (SEO brief
 * "alphahmc-facility-types" part A, Saroth 30 Oct 2026). Managed in Dashboard > Facility types. A card links to a
 * category, a published service or a published package (target "category:ID", "service:ID", "package:ID"); it hides
 * when its target is gone or unpublished. Seeds the 12 starting cards by slug.
 */
return new class extends Migration
{
    private const CARDS = [
        ['Medical centres & polyclinics', 'Multi-specialty outpatient centres, from layout to DHA or DOH licence.', 'category', 'medical-centers-polyclinics'],
        ['Clinics', 'Single-specialty and general clinics across the UAE.', 'service', 'clinic-setup-licensing-uae'],
        ['Day surgery centres', 'Operating theatres, recovery and sterile flows planned for approval.', 'category', 'day-surgery-centers'],
        ['Hospitals', 'Master planning, engineering approvals and licensing for hospitals.', 'category', 'hospitals'],
        ['Diagnostic labs & imaging', 'Laboratory and imaging centres, including radiation approvals.', 'category', 'diagnostic-labs-imaging-centers'],
        ['Rehabilitation centres', 'Physical, speech and paediatric therapy centres.', 'category', 'rehabilitation-centers'],
        ['Home healthcare', 'Licensing and setup for home healthcare providers.', 'category', 'home-healthcare'],
        ['Pharmacies', 'Community and facility pharmacies.', 'category', 'pharmacies'],
        ['Drugstores', 'Pharmaceutical warehousing and distribution licences.', 'category', 'drugstores'],
        ['Telehealth', 'Telehealth services and digital platforms.', 'category', 'telehealth'],
        ['School clinics', 'Clinics inside schools and nurseries.', 'service', 'school-clinic-licensing-uae'],
        ['Any facility in Abu Dhabi', 'DOH facility licensing for every facility type.', 'service', 'doh-healthcare-facility-licensing-abu-dhabi'],
    ];

    public function up(): void
    {
        if (!Schema::hasTable('facility_type_cards')) {
            Schema::create('facility_type_cards', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('line', 255)->nullable();
                $table->string('target', 40);
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('active')->default(true);
                $table->timestamps();
            });
        }

        if (DB::table('facility_type_cards')->exists()) {
            return;
        }
        $tables = ['category' => 'categories', 'service' => 'services', 'package' => 'service_groups'];
        foreach (self::CARDS as $i => [$name, $line, $type, $slug]) {
            $id = DB::table($tables[$type])->where('slug', $slug)->value('id');
            if (!$id) {
                continue;   // target missing on this database: the card is left out rather than pointing nowhere
            }
            DB::table('facility_type_cards')->insert([
                'name' => $name, 'line' => $line, 'target' => "$type:$id", 'sort_order' => $i + 1,
                'active' => true, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('facility_type_cards');
    }
};
