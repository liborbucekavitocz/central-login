<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTokenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_token', function(Blueprint $table)
		{
			$table->increments('user_tokenid');
			$table->integer('userid')->index();
			$table->string('slave_key');
			$table->string('token2');
			$table->tinyInteger('token1_valid')->default(0)->nullable();
			$table->timestamp('token2_expired')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_token');
	}

}
