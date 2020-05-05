<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNodesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('nodes', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->bigInteger('parent_id')->nullable();
			$table->string('uuid', 36);
			$table->string('entity_type')->nullable();
			$table->bigInteger('entity_id')->nullable();
			$table->integer('lft');
			$table->integer('rgt');
			$table->unique(['entity_type','entity_id'], 'node_entity_type_entity_id_unique');
			$table->timestamps();

            $table->foreign('parent_id')->references('id')->on('nodes')->onUpdate('NO ACTION')->onDelete('CASCADE');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('nodes');
	}

}
