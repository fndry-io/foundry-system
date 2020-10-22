<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemPasswordResetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
	    //this is set because digitalocean mysql 8 has this setting on and the create command below won't work without it
        //see https://github.com/laravel/framework/issues/33238
        try {
            \Illuminate\Support\Facades\DB::statement('SET SESSION sql_require_primary_key=0');
        } catch (\Throwable $e) {}

		Schema::create('system_password_resets', function(Blueprint $table)
		{
			$table->string('email');
			$table->string('token');
			$table->dateTime('created_at');
			$table->primary(['email','token']);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_password_resets');
	}

}
