<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateDetalleanticipoimportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalleanticipoimport', function (Blueprint $table) {
            $table->bigIncrements('dant_linea');
            $table->unsignedBigInteger('dant_anticipo');
            $table->foreign('dant_anticipo','fk_detalle_anticipoimport')->references('ant_id')->on('anticipo');
            $table->unsignedBigInteger('dant_tipo');
            $table->foreign('dant_tipo','fk_detalle_polizaimport')->references('poim_id')->on('polizasimportacion');
            $table->string('dant_documento',15)->nullable();
            $table->decimal('dant_monto',14,5);
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
        Schema::dropIfExists('detalleanticipoimport');
    }
}
