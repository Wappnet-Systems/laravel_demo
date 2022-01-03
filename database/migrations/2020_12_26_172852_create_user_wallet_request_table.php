<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWalletRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_wallet_request', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->nullable()->comment('user id');
            
            $table->enum('transfer_type', ['Bank Account', 'Stripe Id'])->nullable()->comment('transfer type');
            
            $table->string('account_holder_name', 255)->nullable()->comment('store account holder name');
            $table->string('account_number', 255)->nullable()->comment('store account number');
            $table->string('ifsc_code', 255)->nullable()->comment('store account ifsc code');
            $table->double('amount', 8, 2)->nullable()->comment('store request amount');
            
            $table->string('stripe_id', 255)->nullable()->comment('store stripe id');
            
            $table->enum('status', ['Pending', 'Approved','Rejected'])->nullable()->default('Pending');
            $table->string('notes', 255)->nullable()->comment('store notes');

            $table->string('created_ip', 255)->nullable()->comment('where create record ip');
            $table->string('updated_ip', 255)->nullable()->comment('where update record ip');
            $table->dateTime('created_at')->nullable();
            $table->dateTime('updated_at')->nullable();

            //foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('user_wallet_request');
    }
}
