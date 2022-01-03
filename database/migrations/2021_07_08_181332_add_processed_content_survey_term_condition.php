<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProcessedContentSurveyTermCondition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('survey_term_condition', function (Blueprint $table) {
            $table->tinyInteger('processed_content')->default('0')->comment('0-not approve, 1-approve')->after('suspicious_content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('survey_term_condition', function (Blueprint $table) {
            //
        });
    }
}
