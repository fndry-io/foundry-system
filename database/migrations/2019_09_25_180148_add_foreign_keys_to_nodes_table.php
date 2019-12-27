<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
			$table->foreign('parent_id', 'FK_1D3D05FC727ACA70')->references('id')->on('nodes')->onUpdate('NO ACTION')->onDelete('CASCADE');
			//$table->foreign('tree_root', 'FK_1D3D05FCA977936C')->references('id')->on('nodes')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('nodes', function(Blueprint $table)
		{
			$table->dropForeign('FK_1D3D05FC727ACA70');
			//$table->dropForeign('FK_1D3D05FCA977936C');
		});
	}

}
