<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateWorklogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worklogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('issue_id')->unsigned()->nullable();
            $table->enum('state', ['running', 'paused', 'finished', 'approved', 'rejected', 'billed'])->default('running');
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('issue_id')
                ->references('id')
                ->on('issues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('worklogs');
    }
}
