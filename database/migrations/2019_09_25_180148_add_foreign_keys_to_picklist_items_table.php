<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPicklistItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('picklist_items', function(Blueprint $table)
		{
			$table->foreign('picklist_id', 'FK_458CA088F0FA04DC')->references('id')->on('picklists')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('picklist_items', function(Blueprint $table)
		{
			$table->dropForeign('FK_458CA088F0FA04DC');
		});
	}

}
