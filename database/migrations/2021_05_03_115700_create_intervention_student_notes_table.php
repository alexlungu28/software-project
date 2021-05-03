<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionStudentNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervention_student_notes', function (Blueprint $table) {
            $table->integer('intervention_id');
            $table->integer('notes_id');
            $table->foreign('intervention_id')->references('intervention_id')->on('intervention')->cascadeOnDelete();
            $table->foreign('notes_id')->references('notes_id')->on('student_notes')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervention_student_notes');
    }
}
