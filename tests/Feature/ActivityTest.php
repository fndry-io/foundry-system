<?php

namespace Foundry\System\Tests\Feature;

use Foundry\System\Models\User;
use Foundry\System\Repositories\ActivityRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivityTest extends TestCase
{
	use DatabaseMigrations;

	public function testActivities()
	{
        $this->login();

        $user = User::query()->find(1);
        $result = ActivityRepository::repository()->create($user, sprintf('%s created the user %s', $user->display_name, $user->display_name), $user);
        $this->assertTrue(!!($result));
        $this->assertDatabaseHas('activities', [
            'reference_type' => User::class,
            'reference_id' => 1,
            'created_by' => $user->display_name,
            'created_by_user_id' => $user->id
        ]);

        $result = ActivityRepository::repository()->browse($user);
        $this->assertEquals(1,  count($result));

        //browse the activities
        $response = $this->json('GET', '/api/system/activities', ['reference_type' => User::class, 'reference_id' => 1]);
        $response->assertOk();
        $response->assertPaginated();
        $result = $response->getData();
        $this->assertEquals(1,  count($result));
        $this->assertSubsetKeys(['id', 'title'], (array) $result[0]);

    }

}
