<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRubricDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rubric_data', function (Blueprint $table) {
            $table->integer('rubric_id');
            $table->integer('row_number')->unsigned();
            $table->integer('value');
            $table->text('note')->nullable();
            //$table->integer('weight'); - for automatic grading indication
            $table->primary(array('rubric_id', 'row_number'));
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
        Schema::dropIfExists('rubric_data');
    }
}
