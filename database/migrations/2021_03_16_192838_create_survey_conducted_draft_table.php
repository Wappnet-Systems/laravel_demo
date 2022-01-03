<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSurveyConductedDraftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_conducted_draft', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('survey_id')->unsigned()->comment('survey id');
            $table->integer('user_id')->unsigned()->nullable()->comment('user id of user');
            $table->string('created_ip', 255)->nullable()->comment('where create suvery quetion ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update suvery quetion ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('user_id')
            ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('survey_id')
            ->references('id')
                ->on('survey')
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
        Schema::dropIfExists('survey_conducted_draft');
    }
}
