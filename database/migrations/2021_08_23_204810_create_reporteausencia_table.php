<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReporteAusenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporteausencia', function (Blueprint $table) {
            $table->increments('rea_id');
            $table->unsignedBigInteger('rea_salario');
            $table->date('rea_inicio');
            $table->date('rea_fin')->nullable();
            $table->string('rea_observaciones',100);
            $table->foreign('rea_salario','fk_reporteausencia_salario')->references('sal_id')->on('salarios');
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
        Schema::dropIfExists('reporteausencia');
    }
}
