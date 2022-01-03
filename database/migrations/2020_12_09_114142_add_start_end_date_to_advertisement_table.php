<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartEndDateToAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisement', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->nullable()->comment('user id');
            $table->enum('media_type', ['Image', 'Video','Youtube Link'])->nullable()->comment('media type');
            $table->string('youtube_link', 255)->nullable()->comment('youtube link');
            $table->enum('advertisement_status', ['Pending', 'Approved','Rejected','Started','Completed','Paused'])->default('Pending')->comment('advertisement status');
            $table->dateTime('start_date')->nullable()->comment('start date time');
            $table->dateTime('end_date')->nullable()->comment('end date time');
            $table->integer('view_count')->nullable()->comment('view count');
            $table->integer('click_count')->nullable()->comment('click count');


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
        Schema::table('advertisement', function (Blueprint $table) {
            $table->dropColumn(['user_id','media_type','youtube_link','advertisement_status','start_date','end_date','view_count','click_count']);
        });
    }
}
