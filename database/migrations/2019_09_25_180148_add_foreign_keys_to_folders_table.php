<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToFoldersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('folders', function(Blueprint $table)
		{
			$table->foreign('parent_id', 'FK_FE37D30F727ACA70')->references('id')->on('folders')->onUpdate('NO ACTION')->onDelete('CASCADE');
			$table->foreign('file_id', 'FK_FE37D30F93CB796C')->references('id')->on('files')->onUpdate('NO ACTION')->onDelete('CASCADE');
			//$table->foreign('tree_root', 'FK_FE37D30FA977936C')->references('id')->on('folders')->onUpdate('RESTRICT')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('folders', function(Blueprint $table)
		{
			$table->dropForeign('FK_FE37D30F727ACA70');
			$table->dropForeign('FK_FE37D30F93CB796C');
			//$table->dropForeign('FK_FE37D30FA977936C');
		});
	}

}
