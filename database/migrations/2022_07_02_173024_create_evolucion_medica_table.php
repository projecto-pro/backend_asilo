<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvolucionMedicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('evolucion_medica', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('correlativo');
            $table->dateTime('fecha_hora');
            $table->string('encargado', 200)->nullable();
            $table->longText('evolucion');

            $table->foreignId('historial_medico_id')->constrained('historial_medico');
            $table->foreignId('ficha_medica_id')->constrained('ficha_medica');
            $table->foreignId('consulta_medica_id')->constrained('consulta_medica');
            $table->foreignId('medico_id')->constrained('medico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('evolucion_medica');
    }
}
