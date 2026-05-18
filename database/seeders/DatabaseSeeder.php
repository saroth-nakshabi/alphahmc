<?php

namespace Database\Seeders;

use App\Models\DeliveryMode;
use App\Models\Inquiry;
use App\Models\PaymentStatus;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Types\Relations\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(SliderSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PermissionCategoriesWithPermissionsSeeder::class);
        $this->call(TagSeeder::class);
        $this->call(GlobaltagsTableSeeder::class);
    }
}