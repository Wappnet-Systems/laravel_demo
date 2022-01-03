<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddBusinessDetailsColumnsToUserInfoTableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_info', function (Blueprint $table) {
            $table->string('business_logo')->nullable()->after('organization_name');
            $table->string('business_contact_number')->nullable()->after('business_logo');
            $table->string('business_contact_email')->nullable()->after('business_contact_number');
            $table->string('business_website')->nullable()->after('business_contact_email');
            $table->string('business_registration_number')->nullable()->after('business_website');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_info', function (Blueprint $table) {
            //
        });
    }
}
