<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInterventionUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intervention_user', function (Blueprint $table) {
            $table->string('user_id');
            $table->integer('intervention_id');
            $table->foreign('user_id')->references('org_defined_id')->on('user')->cascadeOnDelete();
            $table->foreign('intervention_id')->references('intervention_id')->on('intervention')->cascadeOnDelete();
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
        Schema::dropIfExists('intervention_user');
    }
}
