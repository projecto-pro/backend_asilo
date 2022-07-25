<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMensualidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('mensualidad', function (Blueprint $table) {
            $table->id();
            $table->decimal('monto', 10, 2)->default(100);
            $table->boolean('pagado')->default(false);
            $table->date('fecha_pago')->nullable();
            $table->smallInteger('anio');

            $table->foreignId('ingreso_asilo_id')->constrained('ingreso_asilo');
            $table->foreignId('mes_id')->constrained('mes');
            $table->foreignId('usuario_id')->constrained('usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('mensualidad');
    }
}
