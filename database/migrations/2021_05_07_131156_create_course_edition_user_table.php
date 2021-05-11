<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseEditionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_edition_user', function (Blueprint $table) {
            $table->string('user_id');
            $table->foreign('user_id')->references('org_defined_id')->on('users')->cascadeOnDelete();
            $table->foreignId('edition_id')->references('edition_id')->on('course_editions')->cascadeOnDelete();
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
        Schema::dropIfExists('course_edition_user');
    }
}
