<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenRealizadoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('examen_realizado', function (Blueprint $table) {
            $table->id();
            $table->dateTime('examen');
            $table->string('documento')->nullable();

            $table->foreignId('laboratorio_id')->constrained('laboratorio');
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
        Schema::connection(config('database.default'))->dropIfExists('examen_realizado');
    }
}
