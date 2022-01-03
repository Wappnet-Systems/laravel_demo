<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateDemonstrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demonstration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255)->nullable()->comment('enterprise title');
            $table->string('image', 255)->nullable()->comment('enterprise image');
            $table->text('description')->nullable()->comment('enterprise details');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demonstration');
    }
}
