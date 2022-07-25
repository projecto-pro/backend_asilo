<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('persona', function (Blueprint $table) {
            $table->id();
            $table->string('cui', 15)->unique();
            $table->string('primer_nombre', 50);
            $table->string('segundo_nombre', 50)->nullable();
            $table->string('primer_apellido', 50);
            $table->string('segundo_apellido', 50)->nullable();
            $table->string('foto', 100)->nullable(); //Guardaremos la imagen en el local storage
            $table->string('email')->nullable();
            $table->string('ubicacion', 100)->nullable();
            $table->string('telefono', 15)->nullable();
            $table->enum('genero', ['Masculino', 'Femenino']);

            $table->foreignId('departamento_id')->constrained('departamento');
            $table->foreignId('municipio_id')->constrained('municipio');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['cui', 'deleted_at']);
            $table->index('genero');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('persona');
    }
}
