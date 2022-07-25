<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteProveedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('cliente_proveedor', function (Blueprint $table) {
            $table->id();
            $table->string('nit', 15)->unique();

            $table->string('nombre', 200)->nullable();

            $table->string('telefonos')->nullable();
            $table->string('emails')->nullable();
            $table->string('direcciones')->nullable();

            $table->foreignId('departamento_id')->constrained('departamento');
            $table->foreignId('municipio_id')->constrained('municipio');
            $table->foreignId('usuario_id')->constrained('usuario');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['nit', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('cliente_proveedor');
    }
}
