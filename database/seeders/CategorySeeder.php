<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Insert default main categories
        DB::table('main_categories')->insert([
            ['name' => 'For Healthcare Facilities', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Product Registration & Medical Engineering', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'For Healthcare Professionals', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Courses & Trainings', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Insert default service categories
        DB::table('categories')->insert([
            ['main_category_id' => 1,
            'name' => 'Healthcare Facility Licensing',
            'image' => null,
            'description' => "description-for-healthcare-facility-licensing",
            'featured' => true,
            'created_at' => now(),
            'updated_at' => now()],


            ['main_category_id' => 1,
             'name' => 'Healthcare Professional Resourcing',
             'image' => null,
             'description' => 'description-for-healthcare-professional-resourcing',
             'featured' => true,
             'created_at' => now(),
             'updated_at' => now()],


            ['main_category_id' => 2,
            'name' => 'Facility Auditing Accreditation',
            'image' => null,
            'description' => 'description-facility-auditing-accreditation',
             'featured' => true,
             'created_at' => now(),
             'updated_at' => now()],


            ['main_category_id' => 2,
            'name' => 'Healthcare Professional Licensing',
            'image' => null,
            'description' => 'description-healthcare-professional-licensing',
            'featured' => true,
            'created_at' => now(),
            'updated_at' => now()],


            ['main_category_id' => 3,
             'name' => 'Healthcare Management Outsourcing',
             'image' => null,
             'description' => 'description-healthcare-management-outsourcing',
             'featured' => true,
             'created_at' => now(),
             'updated_at' => now()],


            ['main_category_id' => 3,
            'name' => 'Healthcare Feasibility Study',
            'image' => null,
            'description' => 'description-healthcare-feasibility-study',
             'featured' => true,
             'created_at' => now(),
             'updated_at' => now()],


            ['main_category_id' => 4,
            'name' => 'Healthcare Infrastructure Transformation',
            'image' => null,
             'description' => 'description-healthcare-infrastructure-transformation',
             'featured' => true,
             'created_at' => now(),
             'updated_at' => now()],


            ['main_category_id' => 4,
             'name' => 'Healthcare Digital Marketing',
              'image' => null,
              'description' => 'description-healthcare-digital-marketing',
              'featured' => true,
              'created_at' => now(),
              'updated_at' => now()],
        ]);
    }
}