<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervention', function (Blueprint $table) {
            $table->integer('intervention_id')->primary();
            $table->string('group_name');
            $table->integer('edition_id');
            $table->string('content');
            $table->foreign('group_name')->references('group_name')->on('group')->cascadeOnDelete();
            $table->foreign('edition_id')->references('edition_id')->on('course_edition')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervention');
    }
}
