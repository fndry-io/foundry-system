<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('uuid', 36);
			$table->string('email')->unique('UNIQ_1483A5E9E7927C74');
			$table->string('password');
			$table->boolean('active')->default(0);
			$table->boolean('super_admin')->default(0);
			$table->string('timezone')->nullable();
			$table->dateTime('last_login_at')->nullable();
			$table->boolean('logged_in')->default(0);
			$table->string('api_token', 80)->nullable()->unique('UNIQ_1483A5E97BA2F5EB');
			$table->dateTime('api_token_expires_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->string('username', 50)->index();
			$table->string('display_name', 100)->index();
			$table->integer('supervisor_id')->nullable()->index('IDX_1483A5E919E9AC5F');
			$table->string('job_title', 50)->nullable();
			$table->string('job_department', 50)->nullable();
			$table->json('settings')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
