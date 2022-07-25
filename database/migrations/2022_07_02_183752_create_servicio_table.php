<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 25)->unique();
        });

        DB::table('servicio')->insert(
            [
                ['nombre' => 'Agua Potable'],
                ['nombre' => 'Energía Eléctrica'],
                ['nombre' => 'Teléfono'],
                ['nombre' => 'Internet'],
                ['nombre' => 'Cable'],
                ['nombre' => 'Recolección de Basura']
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
        Schema::dropIfExists('servicio');
    }
}
