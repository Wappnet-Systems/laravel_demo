<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTextToEnterpriseSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enterprise_solutions', function (Blueprint $table) {
            $table->text('text')->after('description')->nullable()->comment('for heading');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enterprise_solutions', function (Blueprint $table) {
            $table->dropColumn(['text']);
        });
    }
}
