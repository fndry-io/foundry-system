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
			//$table->bigInteger('tree_root')->nullable()->index('IDX_1D3D05FCA977936C');
			$table->bigInteger('parent_id')->nullable()->index('IDX_1D3D05FC727ACA70');
			$table->string('uuid', 36)->unique('UNIQ_1D3D05FCD17F50A6');
			$table->string('entity_type')->nullable()->index('nodes_node_entity_type_index');
			$table->bigInteger('entity_id')->nullable()->index('nodes_node_entity_id_index');
			$table->integer('lft')->index('nodes_node_lft_index');
			$table->integer('rgt')->index('nodes_node_rgt_index');
			//$table->integer('lvl')->index('nodes_node_lvl_index');
			$table->unique(['entity_type','entity_id'], 'node_entity_type_entity_id_unique');
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
