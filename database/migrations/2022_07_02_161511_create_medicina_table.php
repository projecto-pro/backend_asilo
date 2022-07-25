<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('medicina', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 15)->unique();
            $table->string('nombre', 100);
            $table->string('descripcion', 500);
            $table->string('foto', 100)->nullable(); //Guardaremos la imagen en el local storage

            $table->foreignId('usuario_id')->constrained('usuario');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['codigo', 'deleted_at']);
            $table->index(['nombre', 'deleted_at']);
            $table->index(['codigo', 'nombre', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('medicina');
    }
}
