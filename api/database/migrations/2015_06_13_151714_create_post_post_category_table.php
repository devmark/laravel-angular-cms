<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostPostCategoryTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_post_category', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('post_category_id')->unsigned()->index();
            $table->integer('post_id')->unsigned()->index();

            $table->primary(['post_id', 'post_category_id']);

            $table->foreign('post_id')->references('id')->on('post')->onDelete('cascade');
            $table->foreign('post_category_id')->references('id')->on('post_category')->onDelete('cascade');

        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('post_post_category');
    }

}
