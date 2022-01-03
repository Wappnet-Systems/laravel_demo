<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAdvertisementClickTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_advertisement_click', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertisement_id');
            $table->integer('user_id')->unsigned()->nullable()->comment('user id');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();
            
            //foreign key
            $table->foreign('advertisement_id')
                ->references('id')
                ->on('advertisement')
                ->onDelete('cascade');
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
        Schema::dropIfExists('user_advertisement_click');
    }
}
