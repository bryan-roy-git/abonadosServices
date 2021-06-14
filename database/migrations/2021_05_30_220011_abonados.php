<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Abonados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('abonados', function (Blueprint $table) {
            $table->id();
            $table->string('nif')->nullable();
            $table->string('nombre')->nullable();
            $table->string('apellidos')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('numero_abonado')->unique()->nullable();
            $table->boolean('estado')->nullable();
            $table->foreignId('id_tarifa')->constrained('tarifas');
            $table->boolean('pagado_tarifa')->nullable();
            $table->string('foto')->nullable();
            $table->string('qr')->nullable();
            $table->string('hash')->unique();
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('abonados');
    }
}
