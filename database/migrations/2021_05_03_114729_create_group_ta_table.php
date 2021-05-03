<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupTaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_ta', function (Blueprint $table) {
            $table->integer('group_id');
            $table->string('user_id');
            $table->foreign('group_id')->references('group_id')->on('group')->cascadeOnDelete();
            $table->foreign('user_id')->references('org_defined_id')->on('user')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_ta');
    }
}
