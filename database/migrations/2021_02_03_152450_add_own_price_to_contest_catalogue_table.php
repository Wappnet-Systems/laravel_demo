<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnPriceToContestCatalogueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contest_catalogue', function (Blueprint $table) {
            $table->string('own_product_name')->nullable()->comment('own product name');
            $table->string('own_product_image')->nullable()->comment('own product image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contest_catalogue', function (Blueprint $table) {
            $table->dropColumn(['own_product_name','own_product_image']);
        });
    }
}
