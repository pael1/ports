<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvestigatedCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('investigated_cases', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('days');
            $table->string('receivedby');
            $table->integer('complaint_id');
            $table->integer('assignedto');
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
        Schema::dropIfExists('investigated_cases');
    }
}
