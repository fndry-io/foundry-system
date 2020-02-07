<?php

use Foundry\System\Models\User;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Database\Seeder;

class FoundrySystemSeeder extends Seeder
{
    public function run()
    {
        app(Factory::class)->load(base_path('foundry/system/database/factories'));

        //run sync permissions
        \Illuminate\Support\Facades\Artisan::call('foundry:sync', ['type' => 'permissions']);
        \Illuminate\Support\Facades\Artisan::call('foundry:sync', ['type' => 'picklists']);

        //make the admin user
        $admin = factory(User::class)->create([
            'display_name' => 'Super Admin',
            'username' => 'superadmin',
            'email' => 'superadmin@domain.com',
            'super_admin' => true
        ]);

        //make an admin role
        $admin_role = \Foundry\System\Models\Role::findOrCreate('Admin', 'admin');

        //make a manager role
        $manager_role = \Foundry\System\Repositories\RoleRepository::repository()->insert([
            'name' => 'Manager',
            'guard_name' => 'system'
        ]);
        //make a manager role
        $dummy_role = \Foundry\System\Repositories\RoleRepository::repository()->insert([
            'name' => 'Dummy',
            'guard_name' => 'system'
        ]);

        /** @var User $user */

        //make a series of other users
        $user = factory(User::class)->create([
            'display_name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@domain.com'
        ]);
        $user->syncRoles($admin_role);

        //make a series of other users
        $user = factory(User::class)->create([
            'display_name' => 'Manager',
            'username' => 'manager',
            'email' => 'manager@domain.com'
        ]);
        $user->syncRoles($manager_role);

        //make a series of other users
        $users = [];
        $users[] = factory(User::class)->create([
            'display_name' => 'Dummy 1',
            'username' => 'dummy1',
            'email' => 'dummy1@domain.com'
        ]);
        $users[] = factory(User::class)->create([
            'display_name' => 'Dummy 2',
            'username' => 'dummy2',
            'email' => 'dummy2@domain.com'
        ]);
        $users[] = factory(User::class)->create([
            'display_name' => 'Dummy 3',
            'username' => 'dummy3',
            'email' => 'dummy3@domain.com'
        ]);
        /** @var User $user */
        foreach ($users as $user) {
            $user->syncRoles($dummy_role);
        }
    }

}
