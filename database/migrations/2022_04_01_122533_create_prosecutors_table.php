<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProsecutorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prosecutors', function (Blueprint $table) {
            $table->id();
            $table->string('ext');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('middlename');
            $table->string('reviewer')->nullable();
            $table->string('schedule')->nullable();
            $table->string('court')->nullable();
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
        Schema::dropIfExists('prosecutors');
    }
}
