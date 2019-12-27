<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UpdateTokenInFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		$files = \Illuminate\Support\Facades\DB::table('files')->get();
		foreach ($files as $file) {
		    if (empty($file->token)) {
		        \Illuminate\Support\Facades\DB::table('files')->where('id', $file->id)->update([
		            'token' => \Illuminate\Support\Str::random(32)
                ]);
            }
        }
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

	}

}
