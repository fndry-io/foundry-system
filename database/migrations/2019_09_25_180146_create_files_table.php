<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('uuid', 36)->unique('UNIQ_6354059D17F50A6');
			$table->string('name')->index();
			$table->string('original_name');
			$table->string('type');
			$table->string('ext', 5);
			$table->decimal('size', 18);
			$table->boolean('is_public');
			$table->string('reference_type')->nullable();
			$table->bigInteger('reference_id')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('files');
	}

}
