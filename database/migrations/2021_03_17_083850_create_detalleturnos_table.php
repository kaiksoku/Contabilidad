<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleturnosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalleturnos', function (Blueprint $table) {
            $table->bigIncrements('dett_id');
            $table->integer('dett_turnos');
            $table->integer('dett_extras')->nullable();
            $table->integer('dett_ordinales')->nullable();
            $table->string('dett_descripcion',75);
            $table->unsignedBigInteger('dett_reporte');
            $table->foreign('dett_reporte','fk_detalle_reporteturnos')->references('rept_id')->on('reporteturnos');
            $table->unsignedBigInteger('dett_salario');
            $table->foreign('dett_salario','fk_reporteturnos_salario')->references('sal_id')->on('salarios');
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
        Schema::dropIfExists('detalleturnos');
    }
}
