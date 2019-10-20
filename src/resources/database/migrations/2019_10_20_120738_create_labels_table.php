<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateLabelsTable extends Migration
{
    public function up()
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned();
            $table->string('title', 48);
            $table->string('slug', 64);
            $table->string('color', 16);
            $table->timestamps();

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects');
        });
    }

    public function down()
    {
        Schema::drop('labels');
    }
}
