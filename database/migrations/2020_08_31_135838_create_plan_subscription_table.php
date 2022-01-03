<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlanSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->string('plan_name');
            $table->string('plan_price_label')->nullable();
            $table->decimal('plan_price', 10, 2)->nullable();
            $table->enum('status', ['Enabled', 'Disabled'])->default('Enabled');
            $table->string('created_ip')->nullable();
            $table->string('updated_ip')->nullable();
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
        Schema::dropIfExists('plan_subscription');
    }
}
