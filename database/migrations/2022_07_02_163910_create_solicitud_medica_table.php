<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitudMedicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('solicitud_medica', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->longText('motivo');
            $table->boolean('asignado')->default(false);

            $table->foreignId('ingreso_asilo_id')->constrained('ingreso_asilo');
            $table->foreignId('protegido_id')->constrained('protegido');
            $table->foreignId('usuario_id')->constrained('usuario');
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
        Schema::connection(config('database.default'))->dropIfExists('solicitud_medica');
    }
}
