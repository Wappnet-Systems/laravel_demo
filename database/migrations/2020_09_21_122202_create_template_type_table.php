<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTemplateTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('template_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('template_type', 255)->comment('template type');
            $table->text('template_detail')->nullable()->comment('template detail');
            $table->enum('status', ['Enabled', 'Disabled'])->nullable()->default('Enabled')->comment('template type status');
            $table->string('created_ip', 255)->nullable()->comment('where create template type ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update template type ip');
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
        Schema::dropIfExists('template_type');
    }
}
