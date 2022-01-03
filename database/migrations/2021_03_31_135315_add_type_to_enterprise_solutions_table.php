<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToEnterpriseSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enterprise_solutions', function (Blueprint $table) {
            $table->enum('type', ['content','text'])->default('content')->after('status')->nullable()->comment('which type of content');
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
            $table->dropColumn(['type']);
        });
    }
}
