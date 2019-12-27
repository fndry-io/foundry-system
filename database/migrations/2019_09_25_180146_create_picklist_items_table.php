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
		Schema::create('picklist_items', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('picklist_id')->nullable()->index('IDX_458CA088F0FA04DC');
			$table->string('label', 50)->index();
			$table->text('description')->nullable();
			$table->string('identifier', 100);
			$table->integer('sequence');
			$table->boolean('status');
			$table->boolean('is_system');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('picklist_items');
	}

}
