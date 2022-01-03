<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmountToPaymentTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_transaction', function (Blueprint $table) {
            $table->decimal('amount',8,2)->after('user_id')->nullable()->comment('Transaction amount');
            $table->enum('type', ['Credit','Debit'])->after('transaction_type')->default('Debit')->comment('Transaction credit / debit type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_transaction', function (Blueprint $table) {
            $table->dropColumn(['amount','type']);
        });
    }
}
