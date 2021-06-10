<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGitanalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gitanalyses', function (Blueprint $table) {
            $table->id();
            $table->integer('group_id');
            $table->integer('week_number');
            $table->text('names');
            $table->text('emails');
            $table->text('activity');
            $table->text('blame');
            $table->text('timeline');
            $table->unique(array('group_id', 'week_number'));
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
        Schema::dropIfExists('gitanalyses');
    }
}
