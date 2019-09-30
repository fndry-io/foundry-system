<?php

namespace Foundry\System\Tests\Feature;

use Foundry\Core\Entities\Contracts\IsUser;
use Foundry\Core\Inputs\SimpleInputs;
use Foundry\Core\Support\InputTypeCollection;
use Foundry\System\Inputs\User\UserLoginInput;
use Foundry\System\Repositories\UserRepository;
use Foundry\System\Services\UserService;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsersTest extends TestCase
{
	use RefreshDatabase;

	public function testRepo()
	{
		$data = [
			'username' => 'testuser',
			'display_name' => 'Test User',
			'email' => 'test@domain.com',
			'password' => 'test1234',
		];

		$user = UserRepository::repository()->register($data);
		$this->assertInstanceOf(IsUser::class, $user);

		Auth::setUser($this->admin);

		$data = [
			'username' => 'testinsertuser',
			'display_name' => 'Test Insert User',
			'email' => 'testinsert@domain.com',
			'password' => 'test1234',
		];

		$user = UserRepository::repository()->insert($data);
		$this->assertInstanceOf(IsUser::class, $user);

		$user = UserRepository::repository()->findOneBy(['username' => 'testinsertuser']);

		$data = [
			'username' => 'testupdatetuser',
			'display_name' => 'Test Update User',
			'email' => 'testupdatetuser@domain.com',
			'password' => 'test1234',
		];

		$user = UserRepository::repository()->update($user, $data);
		$this->assertInstanceOf(IsUser::class, $user);

		$data = [
			'one' => '1',
			'two' => '2'
		];

		$user = UserRepository::repository()->syncSettings($user, $data);
		$this->assertArrayHasKey('one', $user->settings);
		$this->assertArrayHasKey('two', $user->settings);

		$password = 'test12345';

		$user = UserRepository::repository()->resetPassword($user, $password);
		$this->assertTrue(Hash::check($password, $user->getAuthPassword()));

	}

	public function testBrowse()
	{
		$user1 = UserRepository::repository()->register([
			'username' => 'testuser',
			'display_name' => 'Test User',
			'email' => 'test1@domain.com',
			'password' => 'test1234',
		]);
		$user2 = UserRepository::repository()->register([
			'username' => 'testuser',
			'display_name' => 'Test User',
			'email' => 'test2@domain.com',
			'password' => 'test1234',
		]);

		$list = UserRepository::repository()->getEmailList('test');
		$this->assertCount(2, $list);

		$list = UserRepository::repository()->getLabelList('test');
		$this->assertCount(2, $list);

		$user2email = UserRepository::repository()->findByEmail('test2@domain.com');
		$this->assertInstanceOf(Paginator::class, $user2email);
		$this->assertCount(1, $user2email->items());

		$browse = UserRepository::repository()->browse([]);
		$this->assertInstanceOf(Paginator::class, $browse);
		$this->assertCount(3, $browse->items());

	}

	public function testService()
	{
		Auth::setUser($this->admin);

		$data = [
			'username' => 'test1',
			'display_name' => 'Test User',
			'email' => 'test1@domain.com',
			'password' => 'test1234',
		];

		$user1 = UserRepository::repository()->insert($data);

		$result = UserService::service()->browse(new SimpleInputs([], InputTypeCollection::fromTypes([])));
		$this->assertTrue($result->isSuccess());

		Auth::logout();

		$response = UserService::service()->login(new UserLoginInput(['email' => 'test1@domain.com', 'password' => 'test1234', 'guard' => 'api']));

		$this->assertTrue($response->isSuccess());
		$this->assertEquals($user1->getKey(), Auth::guard('api')->user()->getKey());

		$user2 = $response->getData();
		$this->assertArrayHasKey('token', $user2);
		$this->assertArrayHasKey('user', $user2);
		$this->assertDatabaseHas('users', ['api_token' => $user2['token']]);

		$response = UserService::service()->logout('api');
		$this->assertTrue($response->isSuccess());
		$this->assertDatabaseMissing('users', ['api_token' => $user2['token']]);

	}

}