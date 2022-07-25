<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIngresoAsiloTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('ingreso_asilo', function (Blueprint $table) {
            $table->id();
            $table->string('correlativo_anual', 15)->unique();
            $table->date('fecha_ingreso');
            $table->date('fecha_egreso')->nullable();
            $table->smallInteger('descuento')->default(10);

            $table->foreignId('protegido_id')->constrained('protegido');
            $table->foreignId('responsable_id')->constrained('persona');
            $table->foreignId('tipo_ingreso_id')->constrained('tipo_ingreso');
            $table->foreignId('usuario_id')->constrained('usuario');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['correlativo_anual', 'deleted_at']);
            $table->index(['fecha_ingreso', 'fecha_egreso', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('ingreso_asilo');
    }
}
