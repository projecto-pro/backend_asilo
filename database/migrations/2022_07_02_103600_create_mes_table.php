<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('mes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 20)->unique();
        });

        DB::connection(config('database.default'))->table('mes')->insert(
            [
                ['nombre' => 'Enero'],
                ['nombre' => 'Febrero'],
                ['nombre' => 'Marzo'],
                ['nombre' => 'Abril'],
                ['nombre' => 'Mayo'],
                ['nombre' => 'Junio'],
                ['nombre' => 'Julio'],
                ['nombre' => 'Agosto'],
                ['nombre' => 'Septiembre'],
                ['nombre' => 'Octubre'],
                ['nombre' => 'Noviembre'],
                ['nombre' => 'Diciembre'],
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
        Schema::connection(config('database.default'))->dropIfExists('mes');
    }
}
