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
		Schema::create('system_folders', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('parent_id')->nullable();
			$table->string('uuid', 36);
			$table->string('name')->index();
			$table->string('reference_type')->nullable()->index();
			$table->bigInteger('reference_id')->nullable()->index();
			$table->integer('lft');
			$table->integer('rgt');
			//$table->integer('lvl');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('file_id')->nullable();
			$table->boolean('is_file')->default(0);

            $table->foreign('parent_id')->references('id')->on('system_folders')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('file_id')->references('id')->on('system_files')->onUpdate('NO ACTION')->onDelete('CASCADE');

        });
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('system_folders');
	}

}
