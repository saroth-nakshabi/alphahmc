<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define the roles you want to seed
        $roles = ['Admin', 'Student', 'Instructor', 'Service Manager', 'Facility'];

        // Loop through each role and create it if it doesn't exist
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}