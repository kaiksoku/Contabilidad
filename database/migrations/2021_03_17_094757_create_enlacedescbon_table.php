<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnlacedescbonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enlacedescbon', function (Blueprint $table) {
            $table->unsignedBigInteger('edb_salario');
            $table->foreign('edb_salario','fk_enlaceDescBon_salario')->references('sal_id')->on('salarios');
            $table->unsignedBigInteger('edb_descbon');
            $table->foreign('edb_descbon','fk_enlaceDescBon_desBon')->references('desc_id')->on('descbon');
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
        Schema::dropIfExists('enlacedescbon');
    }
}
