<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('user id of user');
            $table->string('contest_title', 255)->nullable()->comment('contest title');
            $table->string('contest_nick_name', 255)->nullable()->comment('contest nick name');
            $table->string('contest_age_range', 255)->nullable()->comment('contest target age range');
            $table->enum('gender', ['Male', 'Female'])->nullable()->comment('contest target gender');
            $table->string('contest_applicants', 255)->nullable()->comment('contest applicants in number');
            $table->text('rule_instruction_exclusions')->nullable()->comment('contest rule instruction exclusions');
            $table->text('how_to_apply')->nullable()->comment('contest how to apply');
            $table->string('contest_file', 255)->nullable()->comment('contest file in image and video');
            $table->dateTime('start_date')->nullable()->comment('contest start date time');
            $table->dateTime('end_date')->nullable()->comment('contest end date time');
            $table->enum('admin_status', ['Enabled', 'Disabled'])->nullable()->default('Enabled')->comment('contest admin status');
            $table->enum('contest_status', ['Pending','Started','Completed','Disabled','Draft','Paused'])->nullable()->default('Pending')->comment('contest status');

            $table->string('created_ip', 255)->nullable()->comment('where create contest ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update contest ip');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            
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
        Schema::dropIfExists('contest');
    }
}
