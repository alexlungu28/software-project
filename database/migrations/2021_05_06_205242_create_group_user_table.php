<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_user', function (Blueprint $table) {
            $table->string('group_name');
            $table->string('user_id');
            $table->integer('edition_id')->nullable();
            $table->foreign('group_name')->references('group_name')->on('group')->cascadeOnDelete();
            $table->foreign('user_id')->references('org_defined_id')->on('user')->cascadeOnDelete();
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
        Schema::dropIfExists('group_user');
    }
}
