<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveillanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveillance_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string("fingerprint")->nullable();
            $table->string("userid")->nullable();
            $table->string("ip")->nullable();
            $table->text("url")->nullable();
            $table->text("user_agent")->nullable();
            $table->text("cookies")->nullable();
            $table->text("session")->nullable();
            $table->text("files")->nullable();
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
        Schema::dropIfExists('surveillance_logs');
    }
}
