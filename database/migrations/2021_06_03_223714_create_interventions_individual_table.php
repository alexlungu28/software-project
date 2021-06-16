<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionsIndividualTable extends Migration
{
    /**
     * Create the individual interventions migration.
     * status:
     *        - 1 active
     *        - 2 active - extended
     *        - 3 closed - unsolved
     *        - 4 closed - solved
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interventions_individual', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreignId('group_id')->references('id')->on('groups')->cascadeOnDelete();
            $table->text("reason")->nullable();
            $table->text("action")->nullable();
            $table->date('start_day');
            $table->date('end_day');
            $table->integer('status');
            $table->text('status_note')->nullable();
            $table->integer('visible_ta');

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
        Schema::dropIfExists('interventions_individual');
    }
}
