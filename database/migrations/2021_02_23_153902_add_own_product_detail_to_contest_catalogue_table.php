<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOwnProductDetailToContestCatalogueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contest_catalogue', function (Blueprint $table) {
            $table->text('own_product_detail')->nullable()->comment('own product detail');
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
            $table->dropColumn(['own_product_detail']);
        });
    }
}
