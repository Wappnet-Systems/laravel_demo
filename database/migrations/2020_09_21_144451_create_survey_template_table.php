<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSurveyTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_template', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_name', 255)->comment('template name');
            $table->text('template_detail')->nullable()->comment('template detail');
            $table->integer('template_type_id')->unsigned()->comment('template type id');
            $table->enum('template_price', ['Free', 'Paid'])->default('Free')->comment('template price');
            $table->decimal('template_amount',11,2)->nullable('Free')->comment('template amount');
            $table->enum('status', ['Enabled', 'Disabled'])->nullable()->default('Enabled')->comment('template status');
            $table->string('created_ip', 255)->nullable()->comment('where record create ip');
            $table->string('updated_ip', 255)->nullable()->comment('where record update ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('template_type_id')
            ->references('id')
                ->on('template_type')
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
        Schema::dropIfExists('survey_template');
    }
}
