<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaseStiftTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->integer('client_id')->unsigned();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->primary('id');

            $table->foreign('client_id')
                ->references('id')
                ->on('clients');

        });

        Schema::create('project_users', function(Blueprint $table) {
            $table->increments('id');
            $table->string('project_id');
            $table->integer('user_id')->unsigned();
            $table->timestamps();

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');

        });

        Schema::create('issue_types', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->timestamps();

            $table->primary('id');
        });

        Schema::create('severities', function (Blueprint $table) {
            $table->string('id');
            $table->string('name');
            $table->integer('weight')->unsigned();
            $table->timestamps();

            $table->primary('id');
        });

        Schema::create('project_issue_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('issue_type_id');
            $table->string('project_id');
            $table->timestamps();

            $table->foreign('issue_type_id')
                  ->references('id')
                  ->on('issue_types');

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects');
        });

        Schema::create('project_severities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('severity_id');
            $table->string('project_id');
            $table->timestamps();

            $table->foreign('severity_id')
                  ->references('id')
                  ->on('severities');

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects');
        });

        Schema::create('issues', function (Blueprint $table) {
            $table->increments('id');
            $table->string('project_id');
            $table->string('issue_type_id');
            $table->string('severity_id');
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('status');
            $table->integer('priority')->nullable();
            $table->integer('original_estimate')->nullable();
            $table->date('due_on')->nullable();
            $table->integer('created_by')->unsigned();
            $table->integer('assigned_to')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('project_id')
                  ->references('id')
                  ->on('projects');

            $table->foreign('issue_type_id')
                  ->references('id')
                  ->on('issue_types');

            $table->foreign('severity_id')
                  ->references('id')
                  ->on('severities');

            $table->foreign('created_by')
                  ->references('id')
                  ->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('issues');
        Schema::drop('project_severities');
        Schema::drop('project_issue_types');
        Schema::drop('severities');
        Schema::drop('issue_types');
        Schema::drop('project_users');
        Schema::drop('projects');
    }
}
