<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoIngresoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('tipo_ingreso', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20)->unique();
        });

        DB::table('tipo_ingreso')->insert(
            [
                ['nombre' => 'Temporal'],
                ['nombre' => 'No Temporal']
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('tipo_ingreso');
    }
}
