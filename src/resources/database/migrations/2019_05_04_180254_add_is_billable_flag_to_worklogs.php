<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddIsBillableFlagToWorklogs extends Migration
{
    public function up()
    {
        Schema::table('worklogs', function (Blueprint $table) {
            $table->boolean('is_billable')->default(true);
        });
    }

    public function down()
    {
        Schema::table('worklogs', function (Blueprint $table) {
            $table->dropColumn('is_billable');
        });
    }
}
