<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuspiciousContentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suspicious_content_data', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->comment('user id of user');
            $table->string('advertisement_id')->nullable();
            $table->string('contest_id')->nullable();
            $table->string('survey_id')->nullable();
            $table->string('processed_content_type');
            $table->string('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suspicious_content_data');
    }
}
