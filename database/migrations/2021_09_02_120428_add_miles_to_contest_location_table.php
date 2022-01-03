<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMilesToContestLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contest_location', function (Blueprint $table) {
            $table->string('miles')->nullable()->comment('Show contest in this miles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest_location', function (Blueprint $table) {
            $table->dropColumn(['miles']);
        });
    }
}
