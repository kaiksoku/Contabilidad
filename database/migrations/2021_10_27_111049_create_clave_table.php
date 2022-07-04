<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clave', function (Blueprint $table) {
            $table->unsignedBigInteger('cla_empresa');
            $table->foreign('cla_empresa','fk_clave_empresa')->references('emp_id')->on('empresa');
            $table->primary('cla_empresa');
            $table->string('cla_UsuarioFirma',35);
            $table->string('cla_LlaveFirma',35);
            $table->string('cla_UsuarioApi',35);
            $table->string('cla_LlaveApi',35);
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
        Schema::dropIfExists('clave');
    }
}
