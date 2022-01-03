<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestInterestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_interest', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contest_id')->unsigned()->comment('contest id');
            $table->integer('site_interest_id')->unsigned()->nullable()->comment('interest id');
            $table->string('created_ip', 255)->nullable()->comment('where create contest ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update contest ip');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            //foreign key
            $table->foreign('contest_id')
                ->references('id')
                    ->on('contest')
                    ->onDelete('cascade');
            $table->foreign('site_interest_id')
                ->references('id')
                    ->on('site_interest')
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
        Schema::dropIfExists('contest_interest');
    }
}
