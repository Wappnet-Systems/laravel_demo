<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivityTrackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activity_track', function (Blueprint $table) {
            $table->id();
            $table->string('activity')->nullable();
            $table->string('point')->nullable();
            $table->integer('user_id')->unsigned()->nullable()->comment('user id of user');
            $table->integer('survey_id')->comment('survey id')->nullable();
            $table->integer('contest_id')->comment('contest id')->nullable();
            $table->integer('advertisement_id')->comment('advertisement id')->nullable();
            $table->integer('to_user_id')->comment('to user id')->nullable();
            $table->integer('comment_id')->comment('comment id')->nullable();
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamps();

            //foreign key
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
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
        Schema::dropIfExists('user_activity_track');
    }
}
