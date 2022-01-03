<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestConductedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_conducted', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contest_id')->unsigned()->comment('contest id');
            $table->integer('user_id')->unsigned()->nullable()->comment('user id of user');
            $table->string('contest_link', 255)->nullable()->comment('contest link');
            $table->string('contest_image', 255)->nullable()->comment('contest image');
            $table->string('contest_video', 255)->nullable()->comment('contest video');
            $table->string('contest_audio', 255)->nullable()->comment('contest audio');
            $table->string('created_ip', 255)->nullable()->comment('where create ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('user_id')
            ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('contest_id')
            ->references('id')
                ->on('contest')
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
        Schema::dropIfExists('contest_conducted');
    }
}
