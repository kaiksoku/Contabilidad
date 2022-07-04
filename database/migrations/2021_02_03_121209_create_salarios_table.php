<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salarios', function (Blueprint $table) {
            $table->bigIncrements('sal_id');
            $table->unsignedBigInteger('sal_empresa');
            $table->unsignedBigInteger('sal_terminal');
            $table->unsignedBigInteger('sal_empleado');
            $table->unsignedBigInteger('sal_puesto');
            $table->decimal('sal_salario',10,2);
            $table->boolean('sal_igss')->default(true);
            $table->string('sal_tipo',1);
            $table->date('sal_inicio');
            $table->date('sal_fin')->nullable();
            $table->foreign('sal_empresa','fk_sal_empresa')->references('emp_id')->on('empresa');
            $table->foreign('sal_terminal','fk_sal_terminal')->references('ter_id')->on('terminal');
            $table->foreign('sal_empleado','fk_sal_empleado')->references('empl_id')->on('empleados');
            $table->foreign('sal_puesto','fk_sal_puesto')->references('pues_id')->on('puesto');
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
        Schema::dropIfExists('salarios');
    }
}
