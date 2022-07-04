<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReporteHoraETable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportehorae', function (Blueprint $table) {
            $table->increments('ree_id');
            $table->date('ree_fecha');
            $table->integer('ree_horas');
            $table->string('ree_descripcion',75);
            //Tipo E horas extras //Tipo O Horas Ordinarias
            $table->string('ree_tipo',1);
            $table->unsignedBigInteger('ree_salario');
            $table->foreign('ree_salario','fk_reportehorae_salario')->references('sal_id')->on('salarios');
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
        Schema::dropIfExists('reportehorae');
    }
}
