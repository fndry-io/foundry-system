<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemActivitiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_activities', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('title')->index();
			$table->text('description')->nullable();

			$table->morphs('activitable');
			$table->timestamp('created_at');
			$table->string('created_by');
            $table->unsignedInteger('created_by_user_id')->nullable();
            $table->unsignedBigInteger('node_id')->nullable();

            $table->foreign('node_id')->references('id')->on('nodes')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_activities');
	}

}
