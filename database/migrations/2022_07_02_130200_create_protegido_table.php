<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProtegidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('protegido', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->boolean('activo')->default(true);

            $table->foreignId('persona_id')->constrained('persona');
            $table->foreignId('usuario_id')->constrained('usuario');
            $table->timestamps();

            $table->index(['codigo', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('protegido');
    }
}
