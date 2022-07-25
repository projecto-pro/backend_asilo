<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('medico', function (Blueprint $table) {
            $table->id();
            $table->string('colegiado', 10)->unique();
            $table->string('foto', 100)->nullable(); //Guardaremos la imagen en el local storage
            $table->string('email', 75)->unique();
            $table->string('telefono', 8)->unique();
            $table->boolean('activo')->default(true);

            $table->foreignId('persona_id')->constrained('persona');
            $table->timestamps();

            $table->index(['colegiado', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('medico');
    }
}
