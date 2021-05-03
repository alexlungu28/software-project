<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_courses', function (Blueprint $table) {
            $table->integer('group_id');
            $table->string('course_id');
            $table->string('course_edition');
            $table->foreign('group_id')->references('group_id')->on('group')->cascadeOnDelete();
            $table->foreign('course_id')->references('course_id')->on('course')->cascadeOnDelete();
            $table->foreign('course_edition')->references('course_edition')->on('course_edition')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_courses');
    }
}
