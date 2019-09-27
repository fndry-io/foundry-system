<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePicklistsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('picklists', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('label', 50)->index();
			$table->text('description')->nullable();
			$table->string('identifier', 100);
			$table->integer('default_item')->nullable();
			$table->boolean('is_system');
			$table->timestamps();
			$table->boolean('is_tag')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('picklists');
	}

}
