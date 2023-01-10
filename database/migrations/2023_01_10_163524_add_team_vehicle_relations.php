<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fvehicles', function (Blueprint $table) {
            $table->foreign('model')->references('id')->on('vehicledata')->onDelete('RESTRICT');
            $table->foreign('team')->references('id')->on('team')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fvehicles', function (Blueprint $table) {
            $table->dropForeign(['model']);
            $table->dropForeign(['team']);
        });
    }
};
