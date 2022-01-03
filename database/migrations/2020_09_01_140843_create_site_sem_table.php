<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_sem', function (Blueprint $table) {
            $table->id();
            $table->char('title', 100);
            $table->char('sem_link', 255);
            $table->enum('status', ['Enabled','Disabled']);
            $table->ipAddress('created_ip');
            $table->ipAddress('updated_ip');
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
        Schema::dropIfExists('site_sem');
    }
}
