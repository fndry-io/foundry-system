<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddSystemAppScope extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        \Illuminate\Support\Facades\DB::table('accounts')->insert([
            'name' => 'System',
            'slug' => 'system',
            'created_at' => \Carbon\Carbon::now()
        ]);

		\Illuminate\Support\Facades\DB::table('app_scopes')->insert([
		    'scopeable_type' => Foundry\System\Models\Account::class,
            'scopeable_id' => 1,
            'created_at' => \Carbon\Carbon::now()
        ]);
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
