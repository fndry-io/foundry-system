<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSystemFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('system_files', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('uuid', 36);
			$table->string('name')->index();
			$table->string('original_name');
			$table->string('type');
			$table->string('ext', 20);
			$table->decimal('size', 18);
			$table->boolean('is_public');

			$table->string('reference_type')->nullable();
			$table->unsignedBigInteger('reference_id')->nullable();

            $table->string('token')->index()->nullable();

            $table->string('user_type')->nullable();
            $table->unsignedInteger('user_id')->nullable();
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
		Schema::drop('system_files');
	}

}
