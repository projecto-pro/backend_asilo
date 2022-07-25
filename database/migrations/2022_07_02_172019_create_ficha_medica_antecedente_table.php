<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFichaMedicaAntecedenteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('ficha_medica_antecedente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ficha_medica_id')->constrained('ficha_medica');
            $table->foreignId('antecedente_id')->constrained('antecedente');

            $table->index(['ficha_medica_id', 'antecedente_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('ficha_medica_antecedente');
    }
}
