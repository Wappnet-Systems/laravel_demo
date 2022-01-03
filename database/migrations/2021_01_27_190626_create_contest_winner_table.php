<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestWinnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_winner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contest_id')->unsigned()->comment('contest id');
            $table->integer('user_id')->unsigned()->comment('user id');
            $table->integer('contest_catalogue_id')->unsigned()->comment('contest catalogue id');
            $table->integer('conducted_id')->unsigned()->comment('conducted id');
            $table->string('created_ip', 255)->nullable()->comment('where create ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('contest_id')
                ->references('id')
                ->on('contest')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('contest_catalogue_id')
                ->references('id')
                ->on('contest_catalogue')
                ->onDelete('cascade');
            
            $table->foreign('conducted_id')
                ->references('id')
                ->on('contest_conducted')
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
        Schema::dropIfExists('contest_winner');
    }
}
