<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuestoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puesto', function (Blueprint $table) {
            $table->bigIncrements('pues_id');
            //$table->unsignedBigInteger('pues_empresa');
            //$table->unsignedBigInteger('pues_terminal');
            $table->string('pues_desc_lg',80);
            $table->string('pues_desc_ct',50);
            //$table->foreign('pues_empresa','fk_pues_empresa')->references('emp_id')->on('empresa');
            //$table->foreign('pues_terminal','fk_pues_terminal')->references('ter_id')->on('terminal');
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
        Schema::dropIfExists('puesto');
    }
}
