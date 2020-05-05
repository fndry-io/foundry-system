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
		Schema::create('system_files', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('uuid', 36);
			$table->string('name')->index();
			$table->string('original_name');
			$table->string('type');
			$table->string('ext', 5);
			$table->decimal('size', 18);
			$table->boolean('is_public');
			$table->string('reference_type')->nullable();
			$table->bigInteger('reference_id')->nullable();
            $table->string('token')->index()->nullable();
            $table->unsignedInteger('user_id')->nullable();
			$table->timestamps();
			$table->softDeletes();

            $table->foreign('user_id')->references('id')->on('system_users')->onUpdate('NO ACTION')->onDelete('SET NULL');

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
