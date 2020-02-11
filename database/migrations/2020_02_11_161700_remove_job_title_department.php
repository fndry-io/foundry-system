<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveJobTitleDepartment extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropForeign('FK_1483A5E919E9AC5F');
            $table->dropIndex('IDX_1483A5E919E9AC5F');
            $table->dropColumn(['job_title', 'job_department', 'supervisor_id']);
        });

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('users', function(Blueprint $table)
        {
            $table->string('job_title', 50)->nullable();
            $table->string('job_department', 50)->nullable();
            $table->integer('supervisor_id')->nullable()->index('IDX_1483A5E919E9AC5F');
            $table->foreign('supervisor_id', 'FK_1483A5E919E9AC5F')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('SET NULL');
        });
	}

}
