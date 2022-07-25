<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMunicipioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('municipio', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_original', 4);
            $table->string('codigo', 2);
            $table->string('nombre', 75);
            $table->foreignId('departamento_id')->constrained('departamento');
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
        Schema::connection(config('database.default'))->dropIfExists('municipio');
    }
}
