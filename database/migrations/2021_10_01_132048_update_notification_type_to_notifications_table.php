<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateNotificationTypeToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `notifications` CHANGE `type` `type` ENUM('Friend Request','Follow Friend','Follow Friend Request','Follow Friend Request Accepted','Follow Friend Request Cancelled','Transaction','Survey','Survey Budget Add','Advertisement Payment','Advertisement Click','Advertisement View','Wallet Request Approved','Wallet Request Rejected','Contest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Notification type';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['type']);
        });
    }
}
