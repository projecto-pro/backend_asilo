<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialMedicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('historial_medico', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('correlativo');
            $table->dateTime('fecha_hora_inicio');
            $table->dateTime('fecha_hora_finaliza')->nullable();
            $table->string('encargado')->nullable();
            $table->longText('diagnostico');
            $table->boolean('concluida')->default(false);

            $table->foreignId('ficha_medica_id')->constrained('ficha_medica');
            $table->foreignId('consulta_medica_id')->constrained('consulta_medica');
            $table->foreignId('medico_id')->constrained('medico');

            $table->index('concluida');
            $table->index(['ficha_medica_id', 'consulta_medica_id']);
            $table->index(['fecha_hora_inicio', 'concluida']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('historial_medico');
    }
}
