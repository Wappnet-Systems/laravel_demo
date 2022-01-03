<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertisement', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('advertisement_name')->nullable()->comment('advertisement name');
            $table->string('advertisement_image')->nullable()->comment('advertisement image name');
            $table->string('advertisement_url')->nullable()->comment('advertisement url');
            $table->decimal('budget_amount', 8, 2)->nullable()->comment('budget amount');
            $table->string('age',55)->nullable()->comment('age');
            $table->enum('position_type', ['Timeline Right Side', 'Timeline Between Survey'])->nullable()->comment('advertisement type');
            $table->enum('status', ['Enabled', 'Disabled'])->default('Enabled')->comment('advertisement status');
            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            //foreign key
            $table->foreign('category_id')
                ->references('id')
                ->on('advertisement_category')
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
        Schema::dropIfExists('advertisement');
    }
}
