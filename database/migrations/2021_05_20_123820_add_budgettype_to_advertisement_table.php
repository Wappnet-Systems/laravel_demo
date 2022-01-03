<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBudgettypeToAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advertisement', function (Blueprint $table) {
            $table->integer('campaign_group_id')->unsigned()->nullable()->after('id')->comment('id of campaign_group table');
            $table->enum('objective_type', ['brand_awareness', 'wesite_visit','video_news','lead_generation'])->nullable()->after('advertisement_detail')->comment('objective selection');
            $table->string('language', 255)->nullable()->after('objective_type')->comment('for audience language');
            $table->enum('budget_type',['daily_budget', 'lifetime_budget'])->default('lifetime_budget')->after('advertisement_url')->comment('which type budget');
            $table->enum('schedule_type',['set_continuously', 'set_dates'])->default('set_dates')->after('budget_type')->comment('which type of schedule');
        

             //foreign key
             $table->foreign('campaign_group_id')
             ->references('id')
             ->on('campaign_group')
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
            $table->dropColumn(['campaign_group_id','objective_type','language','budget_type','schedule_type']);
        });
    }
}
