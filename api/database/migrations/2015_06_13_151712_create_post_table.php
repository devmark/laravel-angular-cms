<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->enum('status', ['published', 'draft', 'trash'])->nullable()->default('published');
            $table->enum('visibility', ['public', 'private'])->nullable()->default('public');


            $table->text('title')->nullable();
            $table->longText('content')->nullable();
            $table->string('slug', 128);


            $table->timestamp('published_at');
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
        Schema::drop('post');
    }

}
