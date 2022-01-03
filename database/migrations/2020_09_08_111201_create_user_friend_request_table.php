<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUserFriendRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_friend_request', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('from_user_id')->unsigned()->comment('user id of request user');
            $table->integer('to_user_id')->unsigned()->comment('user id of receive user');
            $table->enum('status', ['Pending','Accepted', 'Rejected'])->nullable()->default('Pending')->comment('user friend request status');
            $table->string('created_ip')->nullable()->comment('where create friend request ip');
            $table->string('updated_ip')->nullable()->comment('where update friend request ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('from_user_id')
            ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('to_user_id')
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
        Schema::dropIfExists('user_friend_request');
    }
}
