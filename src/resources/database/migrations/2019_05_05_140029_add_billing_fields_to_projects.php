<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddBillingFieldsToProjects extends Migration
{
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->boolean('is_billable')->default(true);
            $table->string('pricing_model')->nullable();
            $table->string('pricing_configuration')->nullable();
        });
    }

    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('is_billable');
            $table->dropColumn('pricing_model');
            $table->dropColumn('pricing_model');
        });
    }
}
