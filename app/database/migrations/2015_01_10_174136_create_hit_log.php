<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateHitLog extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hit_log', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('link_id')->unsigned()->nullable()->default(null);
			$table->bigInteger('remoteAddress')->nullable()->default(null);
			$table->string('hostname')->nullable()->default(null);
			$table->string('userAgent')->nullable()->default(null);
			$table->string('referer')->nullable()->default(null);
			$table->nullableTimestamps();

			$table->foreign('link_id')->references('id')->on('links')->onDelete('cascade')->onUpdate('cascade');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hit_log');
	}

}
