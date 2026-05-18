<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Create or find the Admin role
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $studentRole = Role::firstOrCreate(['name' => 'Student']);
        $instructorRole = Role::firstOrCreate(['name' => 'Instructor']);
        $serviceManagerRole = Role::firstOrCreate(['name' => 'Service Manager']);
        $facilityRole = Role::firstOrCreate(['name' => 'Facility']);
        $agentRole = Role::firstOrCreate(['name' => 'Agent']);

        // Create the Admin user
        $adminUser = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone' => '+94751234567',
            'password' => Hash::make('password') // Use a secure password in production
        ]);
        // Assign the Admin role to the user
        $adminUser->assignRole($adminRole);
        $adminUser->admin()->create();

        // Create the agent user
        $agentUser = User::firstOrCreate([
            'email' => 'agent@example.com'
        ], [
            'first_name' => 'agent',
            'last_name' => 'User',
            'phone' => '+94754234561',
            'password' => Hash::make('password') // Use a secure password in production
        ]);
        // Assign the instructor role to the user
        $agentUser->assignRole($agentRole);
        $agentUser->agent()->create([
            'title' => 'Agent',
            'short_description' => 'Agent short description',

        ]);
    }
}