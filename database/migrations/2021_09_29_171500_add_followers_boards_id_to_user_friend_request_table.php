<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFollowersBoardsIdToUserFriendRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_friend_request', function (Blueprint $table) {
            $table->integer('followers_boards_id')->unsigned()->nullable()->comment('followers boards of user')->after('to_user_id');

            $table->foreign('followers_boards_id')->references('id')->on('followers_boards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_friend_request', function (Blueprint $table) {
            $table->dropColumn(['followers_boards_id']);
        });
    }
}
