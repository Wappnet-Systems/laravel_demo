<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePointRankingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_ranking', function (Blueprint $table) {
            $table->id();
            $table->string('rank_title')->nullable()->comment('Rank title');
            $table->string('color')->nullable();
            $table->integer('start_point')->nullable()->comment('point range start');
            $table->integer('end_point')->nullable()->comment('point range end');
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
        Schema::dropIfExists('point_ranking');
    }
}
