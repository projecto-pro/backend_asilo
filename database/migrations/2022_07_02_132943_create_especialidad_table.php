<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspecialidadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('especialidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 75)->unique();
            $table->softDeletes();

            $table->index('deleted_at');
        });

        DB::table('especialidad')->insert(
            [
                ['nombre' => 'Anestesiología'],
                ['nombre' => 'Cirugía General'],
                ['nombre' => 'Cirugía Oral y Maxilofacial'],
                ['nombre' => 'Ginecología Obstetricia'],
                ['nombre' => 'Medicina Física y Rehabilitación'],
                ['nombre' => 'Medicina Interna'],
                ['nombre' => 'Patología'],
                ['nombre' => 'Pediatría'],
                ['nombre' => 'Psiquiatría'],
                ['nombre' => 'Traumatología y Ortopedia'],
                ['nombre' => 'Medicina Crítica del Adulto'],
                ['nombre' => 'Medicina Crítica Pediátrica'],
                ['nombre' => 'Nefrología'],
                ['nombre' => 'Neonatología'],
                ['nombre' => 'Otorrinolaringología'],
                ['nombre' => 'Urología']
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
        Schema::connection(config('database.default'))->dropIfExists('especialidad');
    }
}
