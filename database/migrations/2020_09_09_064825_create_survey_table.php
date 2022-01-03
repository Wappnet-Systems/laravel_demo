<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('user id of user');
            $table->string('title', 255)->nullable()->comment('survey title');
            $table->text('description')->nullable()->comment('survey description');

            $table->enum('servey_type', ['Open Type', 'Close Type'])->nullable()->comment('survey type');
            $table->enum('servey_close_type', ['Paid', 'Unpaid'])->nullable()->comment('survey close type');
            $table->enum('admin_status', ['Enabled', 'Disabled'])->nullable()->default('Enabled')->comment('survey admin status');
            $table->text('survey_status_disable_reason')->nullable()->comment('survey disable reason');
            $table->integer('admin_status_updated_by')->nullable()->comment('id of user which is change survey status');
            $table->dateTime('admin_status_updated_date')->nullable()->comment('survey status change by admin date');

            $table->enum('servey_start',['Auto','Manual', 'Budget Base Survey', 'Base On Number Of Survey'])->nullable()->comment('survey start action');
            $table->enum('survey_status', ['Pending', 'Started', 'Completed', 'Disabled','Draft','Paused'])->nullable()->default('Pending')->comment('user survey status');
            $table->string('survey_last_status', 255)->nullable()->comment('survey disable to enable status');
            $table->dateTime('survey_status_updated_date')->nullable()->comment('survey status change by user date-time');
            $table->dateTime('servey_start_date')->nullable()->comment('survey start date time');
            $table->dateTime('servey_end_date')->nullable()->comment('survey end date time');

            $table->decimal('survey_amount', 11, 2)->nullable()->comment('survey amount');
            $table->string('number_survey_fill', 255)->nullable()->comment('number of survey fill');
            $table->integer('winner_user_id')->nullable()->comment('winner user id');
            $table->integer('survey_template_id')->nullable()->comment('survey template id');
            $table->string('created_ip', 255)->nullable()->comment('where create suvery ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update suvery ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));


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
        Schema::dropIfExists('survey');
    }
}
