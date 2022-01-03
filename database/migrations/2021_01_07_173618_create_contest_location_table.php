<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contest_location', function (Blueprint $table) {
            $table->id();
            $table->integer('contest_id')->unsigned()->comment('contest id');
            $table->string('search_location')->nullable()->comment('full location');
            $table->string('city')->nullable()->comment('location city');
            $table->string('state')->nullable()->comment('location state');
            $table->string('country')->nullable()->comment('location country');
            $table->string('postal_code')->nullable()->comment('location postal_code');
            $table->string('latitude')->nullable()->comment('location latitude');
            $table->string('longitude')->nullable()->comment('location longitude');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            //foreign key
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
        Schema::dropIfExists('contest_location');
    }
}
