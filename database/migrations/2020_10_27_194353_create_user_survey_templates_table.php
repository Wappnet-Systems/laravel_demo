<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSurveyTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_survey_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('user id');
            $table->integer('survey_template_id')->unsigned()->comment('survey template id');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('user_id')
            ->references('id')
                ->on('users')
                ->onDelete('cascade');

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
        Schema::dropIfExists('user_survey_templates');
    }
}
