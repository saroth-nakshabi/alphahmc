<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\PermissionCategory;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

/**
 * Catch-up seeder (2026-06-22): adds view/create/edit/delete permissions for
 * every dashboard area that previously had NO permission gate, following the
 * existing "view|create|edit|delete <area>" convention. Idempotent (firstOrCreate),
 * so it is safe to re-run. All new permissions are granted to the Admin role only
 * — assign subsets to other roles via the permission manager.
 *
 * Keep this list in sync with the gated routes in routes/web.php.
 */
class DashboardAreaPermissionsSeeder extends Seeder
{
    /** Category display name => permission base noun (CRUD verbs prepended). */
    public const AREAS = [
        'Service Groups'    => 'service groups',
        'Brands'            => 'brands',
        'Clients'           => 'clients',
        'Testimonials'      => 'testimonials',
        'Projects'          => 'projects',
        'Project Categories'=> 'project categories',
        'Agents'            => 'agents',
        'Inquiries'         => 'inquiries',
        'Announcements'     => 'announcements',
        'Project Planner'   => 'planner',
        'Planner Builder'   => 'planner builder',
        'Project Process'   => 'project process',
        'Pages & SEO'       => 'pages',
        'Menu Promos'       => 'menu promos',
        'About Us'          => 'about us',
        'About Quote'       => 'about quote',
        'About Counters'    => 'about counters',
        'About Staff'       => 'about staff',
        'Eco System'        => 'eco system',
        'Magazines'         => 'magazines',
        'Settings'          => 'settings',
        'Global Tags'       => 'global tags',
        'Google Tags'       => 'google tags',
    ];

    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);

        foreach (self::AREAS as $categoryName => $base) {
            $category = PermissionCategory::firstOrCreate(['name' => $categoryName]);

            foreach (['view', 'create', 'edit', 'delete'] as $verb) {
                $permission = Permission::firstOrCreate(
                    ['name' => "{$verb} {$base}"],
                    ['guard_name' => 'web']
                );

                // Ensure it is filed under its category (in case it pre-existed uncategorised).
                if ($permission->permission_category_id !== $category->id) {
                    $permission->permission_category_id = $category->id;
                    $permission->save();
                }

                $adminRole->givePermissionTo($permission);
            }
        }
    }
}
