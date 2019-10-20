<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateIssueLabelsTable extends Migration
{
    public function up()
    {
        Schema::create('issue_labels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('issue_id')->unsigned();
            $table->integer('label_id')->unsigned();
            $table->timestamps();

            $table->foreign('issue_id')
                  ->references('id')
                  ->on('issues')
                  ->onDelete('cascade');
            ;

            $table->foreign('label_id')
                  ->references('id')
                  ->on('labels')
                  ->onDelete('cascade');
            ;
        });
    }

    public function down()
    {
        Schema::drop('issue_labels');
    }
}
