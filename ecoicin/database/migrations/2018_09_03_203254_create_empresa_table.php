<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmpresaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresa', function (Blueprint $table) {
            $table->increments('id_empresa');
            $table->string('rut_empresa', 45)->unique();
            $table->string('nombre_empresa', 45)->unique();
            $table->string('telefono_empresa', 15);
            $table->string('email_empresa', 45);
            $table->unsignedInteger('rubro_id');
            $table->timestamps();
            $table->foreign('rubro_id')->references('id_rubro')->on('rubro')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
}
