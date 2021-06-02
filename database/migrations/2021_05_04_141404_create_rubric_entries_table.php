<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRubricEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rubric_entries', function (Blueprint $table) {
            $table->id();
            $table->integer('rubric_id');
            $table->integer('distance')->unsigned();
            $table->boolean('is_row');
            $table->unique(array('rubric_id', 'distance', 'is_row'));
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rubric_entries');
    }
}
