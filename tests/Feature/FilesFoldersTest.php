<?php

namespace Foundry\System\Tests\Feature;

use Foundry\System\Inputs\File\FileInput;
use Foundry\System\Inputs\Folder\FolderEditInput;
use Foundry\System\Inputs\Folder\FolderInput;
use Foundry\System\Inputs\SearchFilterInput;
use Foundry\System\Models\User;
use Foundry\System\Repositories\UserRepository;
use Foundry\System\Services\FileService;
use Foundry\System\Services\FolderService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class FilesFoldersTest extends TestCase
{
	use DatabaseMigrations;

	public function testFolders()
	{
		Auth::setUser($this->admin);

		$data = [
			'name' => 'Test Folder',
			'reference_type' => User::class,
			'reference_id' => 1,
			'folder' => null
		];

		$result = FolderService::service()->add(new FolderInput($data), null);
		$this->assertTrue($result->isSuccess());

		$folder = $result->getData();

		$data = [
			'name' => 'Test Folder Changed'
		];

		$result = FolderService::service()->edit(new FolderEditInput($data), $folder);
		$this->assertTrue($result->isSuccess());

		$result = FolderService::service()->browse($folder, new SearchFilterInput(['search' => null]));
		$this->assertTrue($result->isSuccess());

		$result = FolderService::service()->delete($folder);
		$this->assertTrue($result->isSuccess());

		$result = FolderService::service()->restore($folder);
		$this->assertTrue($result->isSuccess());

		$result = FolderService::service()->delete($folder);
		$this->assertTrue($result->isSuccess());
		$result = FolderService::service()->delete($folder);
		$this->assertTrue($result->isSuccess());
		$this->assertDatabaseMissing('folders', ['id' => $folder->getKey(), "name" => 'Test Folder Changed']);

	}

	public function testFiles()
	{
		Auth::setUser($this->admin);

		$data = [
			'username' => 'test1',
			'display_name' => 'Test User',
			'email' => 'test1@domain.com',
			'password' => 'test1234',
		];

		$user1 = UserRepository::repository()->insert($data);


		$result = FileService::service()->browse($user1, new SearchFilterInput(['search' => null]));
		$this->assertTrue($result->isSuccess());

		$data = [
			'name' => 'Test Folder',
			'reference_type' => User::class,
			'reference_id' => 1,
			'folder' => null
		];

		$result = FolderService::service()->add(new FolderInput($data));
		$this->assertTrue($result->isSuccess());

		$folder = $result->getData();

		$file = UploadedFile::fake()->image('test.jpg');

		$input = FileInput::fromUploadedFile($file, [
			'folder' => $folder->getKey(),
			'reference_type' => User::class,
			'reference_id' => 1,
			'is_public' => true
		]);

		$result = FileService::service()->add($input);
		$this->assertTrue($result->isSuccess());

		$file = $result->getData();

		$this->assertDatabaseHas('folders', ['file_id' => $file->getKey()]);

		$result = FileService::service()->delete($file);
		$this->assertTrue($result->isSuccess());

		$result = FileService::service()->restore($file);
		$this->assertTrue($result->isSuccess());

		$result = FileService::service()->delete($file);
		$this->assertTrue($result->isSuccess());

		$folder = DB::table('folders')->where('file_id', $file->getKey())->first();
		$this->assertNotNull($folder->deleted_at);

		$result = FileService::service()->delete($file);
		$this->assertTrue($result->isSuccess());

		$this->assertDatabaseMissing('files', ['id' => $file->getKey(), "original_name" => 'test.jpg']);
		$this->assertDatabaseMissing('folders', ['file_id' => $file->getKey()]);

	}

}
