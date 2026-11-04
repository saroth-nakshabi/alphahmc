<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * SEO brief "alphahmc-investor-tone-facility-pages" (Saroth approved 4 Nov 2026, guard PASS): the clinic, dental and
 * day surgery pages get the investor-tone slot 4a and 4b from their .slots.md files, word for word (4b section
 * names as h3); Reviewed 4 Nov 2026, Updated = deploy date. Medical centre: the unlinked duplicate of the closing
 * links is removed and "single-speciality" corrected; its Updated date stays.
 * The previous values are saved to storage/app/migration-backups; down() restores them.
 */
return new class extends Migration
{
    private const BACKUP = 'migration-backups/2026_11_04_150000_investor_tone_facility_pages.json';

    private const REVIEWERS = [
        'clinic-setup-licensing-uae' => 'siraj-hm-ibrahim',
        'dental-centre-setup-licensing-dubai' => 'afrin-nakshabi',
        'day-surgery-centre-setup-licensing-uae' => 'afrin-nakshabi',
    ];

    private const COLUMNS = ['info_one', 'info_two', 'reviewer', 'reviewed_at', 'updated_date'];

    private const MEDICAL_DUPLICATE = ' For a single-specialty practice, explore clinic setup in Dubai. For construction or renovation, see medical facility fit-out, or browse our services for medical centres and polyclinics.';

    public function up(): void
    {
        $slugs = array_merge(array_keys(self::REVIEWERS), ['medical-centre-setup-licensing-dubai']);
        $rows = DB::table('services')->whereIn('slug', $slugs)->get(array_merge(['id', 'slug'], self::COLUMNS))->keyBy('slug');
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            Storage::disk('local')->put(self::BACKUP, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }
        $now = now();

        foreach (json_decode(self::TEXT, true) as $slug => $fields) {
            if (!isset($rows[$slug])) {
                continue;
            }
            DB::table('services')->where('id', $rows[$slug]->id)->update($fields + [
                'reviewer' => self::REVIEWERS[$slug],
                'reviewed_at' => '2026-11-04',
                'updated_date' => $now->toDateString(),
                'updated_at' => $now,
            ]);
        }

        if ($medical = $rows['medical-centre-setup-licensing-dubai'] ?? null) {
            $text = str_replace(self::MEDICAL_DUPLICATE, '', (string) $medical->info_two);
            $text = str_replace('single-speciality', 'single-specialty', $text);
            if ($text !== $medical->info_two) {
                DB::table('services')->where('id', $medical->id)->update(['info_two' => $text, 'updated_at' => $now]);
            }
        }
    }

    public function down(): void
    {
        if (!Storage::disk('local')->exists(self::BACKUP)) {
            return;
        }
        foreach (json_decode(Storage::disk('local')->get(self::BACKUP), true) as $row) {
            DB::table('services')->where('id', $row['id'])->update(array_intersect_key($row, array_flip(self::COLUMNS)));
        }
    }

    /** Built from the three .slots.md files by the website terminal; words and links checked against each file. */
    private const TEXT = <<<'JSON'
{
 "clinic-setup-licensing-uae": {
  "info_one": "<h3>What it takes to open your clinic</h3>\n<p>For a small clinic, the licensing project typically takes about 14 weeks; fit-out and authority steps can extend it.</p>",
  "info_two": "<h3>Choosing Your Location</h3><table><thead><tr><th></th><th>Mainland</th><th>DHCC</th></tr></thead><tbody><tr><th scope=\"row\">Trade licence</th><td>DET</td><td>DHCC Free Zone Authority</td></tr><tr><th scope=\"row\">Medical licence (clinic and staff)</th><td>DHA</td><td>DHA</td></tr></tbody></table><p>Your clinic needs a trade licence for the business and a medical licence for the facility. The medical licence comes from DHA in both locations, so the choice affects the trade licence, the premises and how your patients reach you. In the first planning meeting, we compare both options for your specialties, patients and budget, so you decide with a clear view of cost and timing.</p><h3>Defining Your Clinical Services</h3><p>The services you plan to offer determine your facility category, and with it the rooms, staff and equipment your clinic needs. Before design begins, we confirm your services and the facility category with you. This gives you a sound basis for the application, the layout and your budget, and reduces changes once fit-out starts.</p><h3>Designing Your Clinic and Securing Approval</h3><p>DHA approves the layout and engineering drawings before fit-out can start. The layout must suit your services, with the rooms, access and building services they require. Our team prepares the drawings, manages the submission and addresses authority comments until the design is approved, so construction starts on approved plans.</p><h3>Preventing Delays and Extra Costs</h3><ul><li>Review the premises before signing a lease. The unit must fit the rooms, access and services your specialties need. We assess it before you commit, so you avoid a redesign or a second lease.</li><li>Secure approval before starting fit-out. Work built before DHA approves the drawings often has to be redone. We coordinate approvals so the build follows the approved plans.</li><li>Begin staff licensing early. Every doctor and nurse must be licensed individually before opening. We handle <a href=\"https://alphahmc.com/services/dha-license-healthcare-professionals-dubai\">DHA professional licensing</a> for your team alongside the facility application.</li></ul><p>If your project has stalled or did not pass an inspection, we review the approvals, premises and documents, then set out the steps needed to complete licensing and open.</p><p>Outside Dubai, a different authority licenses your clinic: the <a href=\"https://www.doh.gov.ae/\">Department of Health (DOH)</a> in Abu Dhabi, Sharjah Health Authority in Sharjah, and <a href=\"https://mohap.gov.ae/\">MOHAP</a> in the Northern Emirates. See <a href=\"https://alphahmc.com/services/doh-healthcare-facility-licensing-abu-dhabi\">healthcare facility licensing in Abu Dhabi</a>.</p><p>Opening several specialties under one licence? See <a href=\"https://alphahmc.com/services/medical-centre-setup-licensing-dubai\">medical centre and polyclinic setup</a>. Need the build as well? We deliver <a href=\"https://alphahmc.com/services/medical-facility-fit-out\">medical facility fit-out</a>.</p>"
 },
 "dental-centre-setup-licensing-dubai": {
  "info_one": "<h3>What it takes to open your dental centre</h3>\n<p>For dentists opening their own practice, groups adding a dental centre and investors who are not dentists. Here is how we take your centre from planning to licence.</p>",
  "info_two": "<h3>Licensing Your Dental Centre in Dubai</h3><p>We handle the licences and approvals your dental centre needs, from the trade licence to the DHA facility licence. A non-dentist can own a dental centre in Dubai, but the centre needs a licensed medical director. At minimum you also need a licensed nurse and the dentists for the specialties you offer. We handle their DHA licensing alongside the facility licence and keep you informed at each stage.</p><h3>Planning Your Treatment Rooms</h3><p>General dentistry, orthodontics, implants and paediatric dentistry each change the number and type of treatment rooms. We map your specialties against DHA requirements in the first phase, before any drawing. Planning this early makes full use of your space, keeps construction cost under control and shortens the approval path.</p><h3>Designing Sterilisation and Imaging</h3><p>Instruments move from dirty to clean areas without crossing paths, and the number of sterilisation areas depends on the size of the practice. This is a common reason drawings are sent back, so our medical engineering team plans it with the layout. If your centre will have X-ray, we plan the shielded room and the <a href=\"https://www.fanr.gov.ae/\">FANR</a> application into the layout and the schedule from the start.</p><h3>Preventing Delays and Extra Costs</h3><ul><li>Plan the layout before fit-out. A poor layout can lose usable clinical space and raise construction cost. We plan the layout, building services and equipment for your specialties first.</li><li>Secure approval before building. Work built before DHA approves the drawings often has to be redone. We coordinate approvals so construction follows the approved plans.</li><li>Begin staff licensing early. Your medical director, nurse and dentists must be licensed before opening. We start their applications alongside the facility setup.</li></ul><p>If your project has stalled, or your facility did not pass an authority audit, we review where it stands and take it through to opening.</p><p>Need the build as well? We also deliver <a href=\"https://alphahmc.com/services/healthcare-design-and-build\">design and build</a> and <a href=\"https://alphahmc.com/services/medical-facility-fit-out\">medical facility fit-out</a>. For other facility types, see <a href=\"https://alphahmc.com/service-category/facility-licensing-setup\">facility licensing and setup</a> and <a href=\"https://alphahmc.com/services/clinic-setup-licensing-uae\">clinic setup in Dubai</a>.</p>"
 },
 "day-surgery-centre-setup-licensing-uae": {
  "info_one": "<h3>What it takes to open your surgical centre</h3>\n<p>Alpha Health Group plans the theatres and building services with the layout from the first drawing, then carries the facility through licensing, documentation and the final inspection.</p>",
  "info_two": "<h3>Classifying Your Surgical Centre</h3><p>In Dubai, the DHA standards for standalone day surgery centres set how many operating theatres a centre needs for its category. Before any layout work, we confirm the specialties and procedures you plan to offer and the classification they lead to. This sets the number of theatres and the space you need, and gives you a realistic basis for your investment.</p><h3>Designing Your Theatres and Patient Flow</h3><p>Patient, staff, sterile supply and waste routes must not cross, and recovery has to sit next to the theatres. Theatre layout, workflow and the engineering drawings are where most day surgery projects run into difficulty. Our team designs them together, manages the submissions and addresses authority comments until the drawings are approved.</p><h3>Planning Your Building Services</h3><p>Medical gases, air handling for the theatres and backup power are designed together with the layout. Changing them late means reworking the fit-out, with added cost and time. If your theatres will use X-ray or fluoroscopy, the radiation equipment also needs <a href=\"https://www.fanr.gov.ae/\">FANR</a> approval, which we plan into the shielding and the schedule.</p><h3>Who We Work With</h3><p>Surgeons and groups opening their own surgical centre, hospital groups adding an ambulatory site, investors entering surgical care, and owners whose licence has lapsed or whose expansion has stalled. We also take over projects that are part built or did not pass an authority audit, review where they stand and take them through to licence.</p><p>Related: <a href=\"https://alphahmc.com/service-category/day-surgery-centers\">day surgery centre consulting</a> for centres already operating, and <a href=\"https://alphahmc.com/service-category/facility-licensing-setup\">facility licensing and setup</a> for other facility types.</p>"
 }
}
JSON;
};
