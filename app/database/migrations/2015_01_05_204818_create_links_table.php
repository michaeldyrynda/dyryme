<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('hash', 5)->unique();
            $table->string('url')->unique();
            $table->bigInteger('remoteAddress')->nullable();
            $table->string('hostname')->nullable();
            $table->string('userAgent')->nullable();
            $table->nullableTimestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('links');
    }

}
