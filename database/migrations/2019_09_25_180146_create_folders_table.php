<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFoldersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folders', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('tree_root')->nullable()->index('IDX_FE37D30FA977936C');
			$table->integer('parent_id')->nullable()->index('IDX_FE37D30F727ACA70');
			$table->string('uuid', 36)->unique('UNIQ_FE37D30FD17F50A6');
			$table->string('name')->index();
			$table->string('reference_type')->nullable()->index();
			$table->bigInteger('reference_id')->nullable()->index();
			$table->integer('lft');
			$table->integer('rgt');
			$table->integer('lvl');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('file_id')->nullable()->unique('UNIQ_FE37D30F93CB796C');
			$table->boolean('is_file');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('folders');
	}

}
