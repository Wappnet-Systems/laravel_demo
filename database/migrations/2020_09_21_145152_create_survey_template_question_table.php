<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSurveyTemplateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_template_question', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_template_id')->unsigned()->comment('survey template id');
            $table->text('question')->nullable()->comment('survey question');
            $table->text('question_description')->nullable()->comment('survey question description');
            $table->string('question_file', 255)->nullable()->comment('suvery file');
            $table->enum('question_type', ['textbox', 'textarea', 'file', 'option', 'radio', 'checkbox'])->nullable()->comment('survey question type');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('survey_template_id')
            ->references('id')
                ->on('survey_template')
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
        Schema::dropIfExists('survey_template_question');
    }
}
