<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement_location', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertisement_id');
            $table->string('search_location')->nullable()->comment('full location');
            $table->string('city')->nullable()->comment('location city');
            $table->string('state')->nullable()->comment('location state');
            $table->string('country')->nullable()->comment('location country');
            $table->string('postal_code')->nullable()->comment('location postal_code');
            $table->string('latitude')->nullable()->comment('location latitude');
            $table->string('longitude')->nullable()->comment('location longitude');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('advertisement_id')
                ->references('id')
                ->on('advertisement')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('advertisement_location');
    }
}
