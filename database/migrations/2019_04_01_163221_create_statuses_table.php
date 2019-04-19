<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->bigInteger('member_id')->unsigned();
            $table->bigInteger('calendar_id')->unsigned();
            $table->boolean('status');
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members');
            $table->foreign('calendar_id')->references('id')->on('calendars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statuses', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
            $table->dropForeign(['calendar_id']);
        });
        Schema::dropIfExists('statuses');
    }
}
