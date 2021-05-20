<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        $permissions = [
            ['name' => 'create user'],
            ['name' => 'edit user'],
            ['name' => 'view user'],
            ['name' => 'update user'],
            ['name' => 'delete user'],

            ['name' => 'create event'],
            ['name' => 'edit event'],
            ['name' => 'view event'],
            ['name' => 'update event'],
            ['name' => 'delete event'],

            ['name' => 'create flight'],
            ['name' => 'edit flight'],
            ['name' => 'view flight'],
            ['name' => 'update flight'],
            ['name' => 'delete flight'],

            ['name' => 'create movie'],
            ['name' => 'edit movie'],
            ['name' => 'view movie'],
            ['name' => 'update movie'],
            ['name' => 'delete movie'],

            ['name' => 'create ticket'],
            ['name' => 'edit ticket'],
            ['name' => 'view ticket'],
            ['name' => 'update ticket'],
            ['name' => 'delete ticket'],
        ];

        $roles = [
            ['name' => 'admin'],
            ['name' => 'staff'],
            ['name' => 'organizer'],
            ['name' => 'attendee']
        ];

        $role_permissions = [

        ];
        foreach ($permissions as $perm) {
            Permission::create($perm);
        }

        foreach ($roles as $role) {
            $created_role = Role::create($role);

            if ($created_role->name === 'admin' ||  $created_role->name === 'staff') {
                foreach ($permissions as $perm) {
                    $created_role->givePermissionTo($perm);
                }
            }
        }

    }
}
