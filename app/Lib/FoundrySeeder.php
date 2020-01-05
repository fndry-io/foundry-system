<?php


namespace Foundry\System\Lib;


use Illuminate\Database\Seeder;

class FoundrySeeder extends Seeder
{
    protected $users = [];

    /**
     * @param array $manager_perms Manager Permissions
     * @param array $dummy_perms Dummy user Permissions
     */
    public function seedBase($manager_perms, $dummy_perms)
    {
        $this->call(\FoundrySystemSeeder::class);

        $users['manager'] = $this->getUser('manager');
        $users['dummy1'] = $this->getUser('dummy1');
        $users['dummy2'] = $this->getUser('dummy2');
        $users['dummy3'] = $this->getUser('dummy3');

        //assign manager permissions
        $manager_role = \Foundry\System\Models\Role::findByName('Manager');
        $manager_role->givePermissionTo(
            ...$manager_perms
        );

        //assign permissions to dummy's for managing their own statistics
        $dummy_role = \Foundry\System\Models\Role::findByName('Dummy');
        $dummy_role->givePermissionTo(
            ...$dummy_perms
        );

    }

    protected function getUser($username)
    {
        return \Foundry\System\Models\User::query()->where('username', $username)->first();
    }


}
