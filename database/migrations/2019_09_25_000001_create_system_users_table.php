<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('uuid', 36);
            $table->string('username', 50)->index();
            $table->string('display_name', 100)->index();
			$table->string('email');
			$table->string('password');
			$table->boolean('active')->default(0);
			$table->boolean('super_admin')->default(0);
			$table->string('timezone')->nullable();
			$table->dateTime('last_login_at')->nullable();
			$table->boolean('logged_in')->default(0);
			$table->string('api_token', 80)->nullable();
			$table->dateTime('api_token_expires_at')->nullable();
			$table->json('settings')->nullable();
            $table->timestamps();
            $table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_users');
	}

}
