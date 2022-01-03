<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaximumEntryToContestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contest', function (Blueprint $table) {
            $table->enum('maximum_entry', ['Multiple', 'Only one'])->nullable()->after('contest_status')->comment('maximum entry');
            $table->string('multiple_day')->nullable()->after('maximum_entry')->comment('multiple day');
            $table->string('multiple_hour')->nullable()->after('multiple_day')->comment('multiple hour');
            $table->string('multiple_minute')->nullable()->after('multiple_hour')->comment('multiple minute');
            $table->string('multiple_second')->nullable()->after('multiple_minute')->comment('multiple second');
            $table->string('number_maximum_entry_limit')->nullable()->after('multiple_second')->comment('number maximum entry limit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest', function (Blueprint $table) {
            $table->dropColumn(['maximum_entry','multiple_day','multiple_hour','multiple_minute','multiple_second','number_maximum_entry_limit']);
        });
    }
}
