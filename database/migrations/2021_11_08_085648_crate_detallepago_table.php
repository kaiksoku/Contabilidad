<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateDetallepagoTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detallepago', function (Blueprint $table) {
            $table->unsignedBigInteger('dp_empresa');
            $table->foreign('dp_empresa','fk_dp_empresa')->references('emp_id')->on('empresa');
            $table->string('dp_numero',100);//No Orden
            $table->string('dp_serie',100);//Duca
            $table->unsignedBigInteger('dp_pago');
            $table->string('dp_tipo',1);//Cheque, transferencia, anticipo
            $table->primary('dp_empresa', 'dp_numero', 'dp_serie', 'dp_pago');
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
        Schema::dropIfExists('detallepago');
    }
}
