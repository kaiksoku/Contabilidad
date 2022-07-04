<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLiquidacionccTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liquidacioncc', function (Blueprint $table) {
            $table->bigIncrements('lcc_id');
            $table->string('lcc_descripcion',50);
            $table->date('lcc_fecha');
            $table->unsignedBigInteger('lcc_cajachica');
            $table->foreign('lcc_cajachica','fk_liquidacionCajaC_cajachica')->references('cch_id')->on('cajachica');
            $table->unsignedBigInteger('lcc_transaccion')->nullable();
            $table->foreign('lcc_transaccion','fk_liquidacionCajaC_transaccion')->references('trab_id')->on('transaccionbancaria');
            $table->boolean('lcc_pendiente')->nullable();
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
        Schema::dropIfExists('liquidacioncc');
    }
}
