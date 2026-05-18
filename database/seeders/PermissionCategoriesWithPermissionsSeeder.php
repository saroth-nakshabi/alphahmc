<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use PhpParser\Node\Expr\Assign;
use Spatie\Permission\Models\Role;

class PermissionCategoriesWithPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define categories and their permissions
        $categories = [
            'Service' => [
                'view services',
                'view create services',
                'create services',
                'edit services',
                'delete services',
            ],
            'Category' => [
                'view categories',
                'create categories',
                'edit categories',
                'delete categories',
            ],
            'Profile' => [
                'view profile',
                'edit profile',
                'delete profile',
            ],
            // Subcategory: Admins
            'Admins' => [
                'view admins',
                'create admins',
                'edit admins',
                'delete admins',
            ],
            // Subcategory: Users
            'Users' => [
                'view users',
                'create users',
                'edit users',
                'delete users',
            ],
            // Subcategories for Roles
            'Roles' => [
                'view roles',
                'create roles',
                'edit roles',
                'delete roles',
            ],
            // Subcategories for Permissions
            'Permissions' => [
                'view permissions',
                'create permissions',
                'edit permissions',
                'delete permissions',
            ],
            // Subcategories for Permission Categories
            'Permissions Categories' => [
                'view permissions categories',
                'create permissions categories',
                'edit permissions categories',
                'delete permissions categories',
            ],
            'Home Slider' => [
                'view home sliders',
                'create home sliders',
                'edit home sliders',
                'delete home sliders',
            ],
            'Blogs' => [
                'view blogs',
                'create blogs',
                'edit blogs',
                'delete blogs',
            ],
            'Tags' => [
                'view tags',
                'create tags',
                'edit tags',
                'delete tags',
            ],
            'Test Questions' => [
                'view test questions',
                'create test questions',
                'edit test questions',
                'delete test questions',
            ],
            'Test Answers' => [
                'view test answers',
                'create test answers',
                'edit test answers',
                'delete test answers',
            ],

           'Main Category' => [
                'view main categories',
                'create main categories',
                'edit main categories',
                'delete main categories',
            ],

            'Tab services' => [
              'view tab services',
                // 'create main categories',
                // 'edit main categories',
                // 'delete main categories',
            ],
            // Add more categories and permissions as needed
        ];

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        // Loop through each category
        foreach ($categories as $categoryName => $permissions) {
            // Create the category
            $category = PermissionCategory::create([
                'name' => $categoryName,
            ]);

            // Create each permission for the category
            foreach ($permissions as $permissionName) {
                $permission = Permission::create([
                    'name' => $permissionName,
                    'permission_category_id' => $category->id,
                ]);
                // Assign all the permissions to Admin role
                $adminRole->givePermissionTo($permission);
            }
        }
    }
}
