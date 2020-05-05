<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddProfileImageToUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('system_users', function(Blueprint $table)
		{
            $table->integer('profile_image_id')->nullable();
            $table->foreign('profile_image_id')->references('id')->on('system_files')->onUpdate('NO ACTION')->onDelete('SET NULL');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('system_users', function(Blueprint $table)
        {
            $table->dropForeign('system_users_profile_image_id_foreign');
            $table->dropColumn('profile_image_id');
        });
	}

}
