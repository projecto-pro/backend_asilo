<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConsultaMedicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('consulta_medica', function (Blueprint $table) {
            $table->id();
            $table->dateTime('fecha_hora');
            $table->time('hora');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->boolean('pagado')->default(false);
            $table->boolean('atendido')->default(false);
            $table->boolean('anulado')->default(false);

            $table->foreignId('solicitud_medica_id')->constrained('solicitud_medica');
            $table->foreignId('medico_id')->constrained('medico');
            $table->foreignId('usuario_id')->constrained('usuario');
            $table->foreignId('mes_id')->constrained('mes');
            $table->timestamps();

            $table->index('fecha_hora');
            $table->index(['fecha_hora', 'medico_id']);
            $table->index(['pagado', 'mes_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('consulta_medica');
    }
}
