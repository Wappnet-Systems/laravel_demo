<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampaignGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaign_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_name', 255)->nullable()->comment('campaign group title');
            $table->enum('status',['Enabled', 'Disabled'])->default('Enabled');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
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
        Schema::dropIfExists('campaign_group');
    }
}
