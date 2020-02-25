<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddTokenForDeletingToFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('files', function(Blueprint $table)
		{
            $table->integer('user_id')->nullable();
			$table->string('token')->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('files', function(Blueprint $table)
        {
            $table->dropForeign('files_user_id_foreign');
        });
        Schema::table('files', function(Blueprint $table)
        {
            $table->dropColumn('token');
        });
        Schema::table('files', function(Blueprint $table)
        {
            $table->dropColumn('user_id');
        });
	}

}
