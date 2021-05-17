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
            $table->id();
            $table->integer('rubric_id');
            $table->integer('row_number')->unsigned();
            $table->integer('value');
            $table->text('note')->nullable();
//            $table->integer('user_id'); - To keep track of the latest person that edited this row
            //$table->integer('weight'); - for automatic grading indication
            $table->unique(array('rubric_id', 'row_number'));
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
        Schema::dropIfExists('rubric_data');
    }
}
