<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichaMedicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('ficha_medica', function (Blueprint $table) {
            $table->id();
            $table->string('expediente')->unique();
            $table->date('fecha_ingreso');
            $table->time('hora_ingreso');
            $table->date('fecha_nacimiento');
            $table->string('cui', 15)->unique();
            $table->string('nombre_completo', 200);
            $table->enum('genero', ['Masculino', 'Femenino']);
            $table->string('direccion', 500)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->longText('alergia')->nullable();

            $table->foreignId('protegido_id')->constrained('protegido');
            $table->foreignId('ingreso_asilo_id')->constrained('ingreso_asilo');
            $table->foreignId('contacto_id')->constrained('persona');
            $table->foreignId('solicitud_medica_id')->constrained('solicitud_medica');
            $table->foreignId('medico_tratante_id')->constrained('medico');
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
        Schema::connection(config('database.default'))->dropIfExists('ficha_medica');
    }
}
