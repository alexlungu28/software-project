<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->integer('problem_signal');
            $table->integer('noteable_id');
            $table->string('noteable_type');
//            $table->unsignedBigInteger('group_id')->nullable();
//            $table->unsignedBigInteger('intervention_id')->nullable();

//            $table->foreign('group_id')->references('id')->on('groups')->cascadeOnDelete();
//            $table->foreign('intervention_id')->references('id')->on('interventions')->cascadeOnDelete();
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
        Schema::dropIfExists('notes');
    }
}
