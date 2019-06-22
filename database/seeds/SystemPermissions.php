<?php

use Illuminate\Database\Seeder;
use LaravelDoctrine\ORM\Facades\EntityManager;

class SystemPermissions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = include('../../config/permissions.php');
        $this->seedPermissions($permissions);
    }

    public function seedPermissions(array $permissions = [])
    {
	    foreach ($permissions as $group => $names) {
		    foreach ($names as $name) {
			    $perm = new \Foundry\System\Entities\Permission();
			    $perm->setName($name);
			    $perm->setGroup($group);
			    EntityManager::persist($perm);
		    }
	    }
	    EntityManager::flush();
    }
}
