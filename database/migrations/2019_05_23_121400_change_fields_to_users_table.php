<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('title')->nullable()->change();
            $table->string('address', 255)->nullable()->change();
            $table->string('city', 128)->nullable()->change();
            $table->string('region', 128)->nullable()->change();
            $table->string('country', 128)->nullable()->change();
            $table->string('postcode', 64)->nullable()->change();
            $table->string('phone', 64)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
