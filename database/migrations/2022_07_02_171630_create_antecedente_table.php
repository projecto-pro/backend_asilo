<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAntecedenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('antecedente', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 75)->unique();
            $table->softDeletes();

            $table->index('deleted_at');
        });

        DB::table('antecedente')->insert(
            [
                ['nombre' => 'Hipertensión'],
                ['nombre' => 'VIH'],
                ['nombre' => 'Parkinson'],
                ['nombre' => 'EPOC'],
                ['nombre' => 'TBC'],
                ['nombre' => 'Demencia'],
                ['nombre' => 'Diabetes'],
                ['nombre' => 'ACV Secuelas'],
                ['nombre' => 'Enfermedad Terminal'],
                ['nombre' => 'Insuficiencia Renal'],
                ['nombre' => 'COVID 19']
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
        Schema::connection(config('database.default'))->dropIfExists('antecedente');
    }
}
