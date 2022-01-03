<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuickPollLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quick_poll_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->comment('user id of user');
            $table->integer('quick_poll_id')->unsigned()->nullable()->comment('id of quick_poll');
            $table->text('quick_poll_option')->nullable()->comment('selected option of quick poll question');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamps();

            //foreign key
            $table->foreign('user_id')
            ->references('id')
                ->on('users')
                ->onDelete('cascade');

                $table->foreign('quick_poll_id')
                ->references('id')
                    ->on('quick_poll')
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
        Schema::dropIfExists('quick_poll_logs');
    }
}
