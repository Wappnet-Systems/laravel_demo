<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesToQuickPollTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quick_poll', function (Blueprint $table) {
            $table->date('start_date')->after('status')->nullable()->comment('poll start date');
            $table->date('end_date')->after('start_date')->nullable()->comment('poll end date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quick_poll', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
        });
    }
}
