<?php

namespace Foundry\System\Tests\Feature;

use Foundry\System\Models\Permission;
use Foundry\System\Models\User;
use Foundry\System\Repositories\RoleRepository;
use Foundry\System\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
	use DatabaseMigrations;

	public function testRoles()
	{
	    $this->login();

	    $data = [
	        'name' => 'Test Role 1',
            'guard_name' => 'system'
        ];
	    $role1 = RoleRepository::repository()->insert($data);
        $this->assertDatabaseHas('roles', $data);
	    $this->assertNotFalse($role1);

	    $data = [
            'name' => 'Test Role 1 edited'
        ];
        $role1 = RoleRepository::repository()->update($role1, $data);
        $this->assertDatabaseHas('roles', $data);
        $this->assertNotFalse($role1);

        $data = [
            'name' => 'Test Role 2',
            'guard_name' => 'system'
        ];
        $role2 = RoleRepository::repository()->insert($data);
        $this->assertDatabaseHas('roles', $data);
        $this->assertNotFalse($role2);

        $result = RoleRepository::repository()->browse([]);
        $this->assertInstanceOf(Paginator::class, $result);
        $this->assertCount(2, $result->items());

        $result = RoleRepository::repository()->delete($role2);
        $this->assertDatabaseMissing('roles', $data);
        $this->assertTrue($result);

        //make some permissions
        $permission1 = Permission::create(['name' => 'edit articles']);
        $permission2 = Permission::create(['name' => 'delete articles']);
        $permission3 = Permission::create(['name' => 'create articles']);

        RoleRepository::repository()->syncPermissions($role1, [
            $permission1->getKey(),
            $permission2->getKey(),
            $permission3->getKey()
        ]);

        $permissions = RoleRepository::repository()->permissions($role1);
        $this->assertInstanceOf(Paginator::class, $permissions);
        $this->assertCount(3, $permissions->items());

        $data = [
            'name' => 'Test Role 3',
            'guard_name' => 'system'
        ];
        $role3 = RoleRepository::repository()->insert($data);
        $this->assertDatabaseHas('roles', $data);
        $this->assertNotFalse($role3);

        $result = RoleRepository::repository()->syncRolePermissions([
            $role3->getKey() => [
                $permission1->getKey(),
                $permission2->getKey(),
                $permission3->getKey()
            ]
        ]);
        $this->assertTrue($result);

        $user = UserRepository::repository()->insert([
            'username' => 'testuser',
            'display_name' => 'Test User',
            'email' => 'test@domain.com',
            'password' => 'testuser1',
            'roles' => [$role1->getKey()]
        ]);
        $this->assertNotFalse($user);
        $this->assertDatabaseHas('model_has_roles', [
            'model_type' => User::class,
            'model_id' => $user->getKey(),
            'role_id' => $role1->getKey()
        ]);

        $permissions = $user->getAllPermissions()->pluck('name')->toArray();
        $this->assertEmpty(array_diff(['edit articles', 'delete articles', 'create articles'], $permissions));

    }

}
