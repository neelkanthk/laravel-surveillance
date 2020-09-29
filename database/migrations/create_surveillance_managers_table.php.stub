<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSurveillanceManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('surveillance_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->string("type");
            $table->string("value");
            $table->unsignedTinyInteger("surveillance_enabled")->nullable();
            $table->unsignedTinyInteger("access_blocked")->nullable();
            $table->timestamp("surveillance_enabled_at")->nullable();
            $table->timestamp("surveillance_disabled_at")->nullable();
            $table->timestamp("access_blocked_at")->nullable();
            $table->timestamp("access_unblocked_at")->nullable();
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
        Schema::dropIfExists('surveillance_managers');
    }
}
