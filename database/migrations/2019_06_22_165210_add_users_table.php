<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid('uuid');
	        $table->string('email', 250)->unique();
	        $table->string('password')->unique();
            $table->string('first_name', 100);
	        $table->string('last_name', 100);
	        $table->boolean('active')->default(0);
	        $table->boolean('super_admin')->default(0);
	        $table->string('timezone', 50)->nullable();
	        $table->boolean('logged_in')->default(0);
	        $table->dateTime('last_login_at')->nullable();
	        $table->boolean('remember_token')->default(0);
	        $table->boolean('api_token')->default(0);
	        $table->dateTime('api_token_expires_at')->nullable();

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
        Schema::dropIfExists('users');
    }
}
