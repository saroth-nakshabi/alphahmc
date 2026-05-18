<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlobaltagsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('globaltags')->insert([
            'globaltag_name' => 'JSON-LD Organization & Website',
            'tags' => '<script type="application/ld+json">
[
  {
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Alpha Healthcare Consultations",
        "AlternativeName": "Alpha Health Group",
    "url": "https://alphatsm.com",
    "logo": "https://yourwebsite.com/public/favicon.png",
    "sameAs": [
      "https://www.linkedin.com/company/alphatsm/",
      "https://www.facebook.com/alphatsm",
      "https://www.instagram.com/alpha_tsm/"
    ]
  },
  {
    "@context": "https://schema.org",
    "@type": "WebSite",
    "url": "https://alphatsm.com",
    "potentialAction": {
      "@type": "SearchAction",
      "target": "https://alphatsm.com/search?q={search_term_string}",
      "query-input": "required name=search_term_string"
    }
  }
]
</script>',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}