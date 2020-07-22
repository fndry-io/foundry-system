<?php

namespace Foundry\System\Tests\Feature;

use Foundry\System\Models\User;
use Foundry\System\Testing\InteractsWithAuth;
use Foundry\System\Testing\SeedsFoundrySystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\TestCase;

class SystemUsersTest extends TestCase
{
    use RefreshDatabase;
    use InteractsWithAuth;
    use SeedsFoundrySystem;

    public function testUsersAdd()
    {
        $this->loginAs('admin', 'system');

        //the data we will send to the server
        $data = [
            'username' => 'testuser',
            'display_name' => 'Test User',
            'email' => 'test@domain.com',
            'password' => 'Test#1234',
            'password_confirmation' => 'Test#1234'
        ];

        //the json call
        $response = $this->json('POST', '/api/system/users/add', $data);

        //asserting the expected Foundry Response of status = true
        $response->assertOk();

        //check the database has this new user
        $this->assertDatabaseHas('system_users', Arr::only($data, [
            'username',
            'display_name',
            'email'
        ]));
    }

    public function testUsersEdit()
    {
        $user = factory(User::class)->create();

        $this->loginAs('admin', 'system');

        //the data we will send to the server
        $data = [
            'username' => 'testuser',
            'display_name' => 'Test User',
            'email' => 'test@domain.com',
            'password' => 'Test#1234',
            'password_confirmation' => 'Test#1234'
        ];

        //the json call
        $response = $this->json('POST', '/api/system/users/'.$user->id.'/edit', $data);

        //asserting the expected Foundry Response of status = true
        $response->assertOk();

        //check the database has this new user
        $this->assertDatabaseHas('system_users', Arr::only($data, [
            'username',
            'display_name',
            'email'
        ]));
    }

    public function testUsersDelete()
    {
        $user = factory(User::class)->create();

        $this->loginAs('admin', 'system');

        //the json call
        $response = $this->json('POST', '/api/system/users/'.$user->id.'/delete');

        //asserting the expected Foundry Response of status = true
        $response->assertOk();

        //check the database has this new user
        $this->assertDatabaseMissing('system_users', [
            'id' => $user->id,
            'deleted_at' => null
        ]);
    }

    public function testUsersRestore()
    {
        $user = factory(User::class)->create();
        $id = $user->id;
        $user->delete();

        //check the database has this new user
        $this->assertDatabaseMissing('system_users', [
            'id' => $id,
            'deleted_at' => null
        ]);

        $this->loginAs('admin', 'system');

        //the json call
        $response = $this->json('POST', '/api/system/users/'.$id.'/restore');

        //asserting the expected Foundry Response of status = true
        $response->assertOk();

        //check the database has this new user
        $this->assertDatabaseHas('system_users', [
            'id' => $id,
            'deleted_at' => null
        ]);
    }

    public function testUsersRead()
    {
        $user = factory(User::class)->create();

        $this->loginAs('admin', 'system');

        //the json call
        $response = $this->json('GET', '/api/system/users/'.$user->id);

        //asserting the expected Foundry Response of status = true
        $response->assertOk();

        $result = (array) $response->getData();

        //check the database has this new user
        $this->assertEquals(Arr::only($result, [
            'id',
            'username'
        ]), [
            'id' => $user->id,
            'username' => $user->username
        ]);
    }

    public function testUsersBrowse()
    {
        //We should get these plus the users seeded through SeedsFoundrySystem
        $users = factory(User::class, 4)->create();

        $this->loginAs('admin', 'system');

        //the json call
        $response = $this->json('GET', '/api/system/users');

        //asserting the expected Foundry Response of status = true
        $response->assertOk();
        $response->assertPaginated();
        $result = $response->getData();

        //check the database has this new user
        $this->assertCount(10, $result);
    }



}
