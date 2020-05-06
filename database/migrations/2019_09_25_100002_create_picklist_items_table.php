<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePicklistItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_picklist_items', function(Blueprint $table)
		{
            $table->increments('id');
			$table->unsignedInteger('picklist_id')->nullable();
			$table->string('label', 50)->index();
			$table->text('description')->nullable();
			$table->string('identifier', 100);
			$table->integer('sequence');
			$table->boolean('status');
			$table->boolean('is_system');
			$table->timestamps();

            $table->foreign('picklist_id')->references('id')->on('system_picklists')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_picklist_items');
	}

}
