<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDescbonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('descbon', function (Blueprint $table) {
            $table->bigIncrements('desc_id');
            $table->unsignedBigInteger('desc_tipo');
            $table->foreign('desc_tipo','fk_descbon_tipodesc')->references('tipd_id')->on('tipodesc');
            $table->unsignedDecimal('desc_monto',8,2);
            $table->date('desc_inicio');
            $table->date('desc_fin')->nullable();
            $table->unsignedBigInteger('desc_cuentaContable');
            $table->foreign('desc_cuentacontable','fk_descbon_cuentacontable')->references('cta_id')->on('cuentacontable');
            $table->unsignedBigInteger('desc_empresa');
            $table->foreign('desc_empresa','fk_desc_empresa')->references('emp_id')->on('empresa');
            $table->unsignedBigInteger('desc_terminal');
            $table->foreign('desc_terminal','fk_desc_terminal')->references('ter_id')->on('terminal');
            $table->boolean('desc_general');
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
        Schema::dropIfExists('descbon');
    }
}
