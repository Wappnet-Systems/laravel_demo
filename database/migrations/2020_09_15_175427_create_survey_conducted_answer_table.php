<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSurveyConductedAnswerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_conducted_answer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_conducted_id')->unsigned()->comment('survey conducted id');
            $table->integer('question_id')->unsigned()->comment('question id');
            $table->integer('option_id')->nullable()->comment('option id');
            $table->text('answer_text')->nullable()->comment('answer in text like textbox,textarea,file');
            $table->string('created_ip', 255)->nullable()->comment('where create suvery quetion ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update suvery quetion ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('survey_conducted_id')
            ->references('id')
                ->on('survey_conducted')
                ->onDelete('cascade');

            $table->foreign('question_id')
            ->references('id')
                ->on('survey_question')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('survey_conducted_answer');
    }
}
