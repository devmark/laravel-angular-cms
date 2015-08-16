<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('media_category_id')->unsigned()->index()->nullable();
            $table->string('path', 255)->nullable();
            $table->bigInteger('filesize')->unsigned()->default(0);
            $table->string('name')->nullable()->index();
            $table->string('key');
            $table->string('mime');
            $table->timestamps();

            $table->foreign('media_category_id')->references('id')->on('media_category')->onDelete('restrict');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media');
    }

}
