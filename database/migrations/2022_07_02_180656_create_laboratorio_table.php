<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaboratorioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('laboratorio', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_protegido', 20);
            $table->string('examen', 100);
            $table->decimal('precio', 10, 2);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento_aplicado', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->boolean('cancelado')->default(false);
            $table->boolean('realizado')->default(false);

            $table->foreignId('protegido_id')->constrained('protegido');
            $table->foreignId('evolucion_medica_id')->constrained('evolucion_medica');
            $table->foreignId('examen_id')->constrained('examen');
            $table->foreignId('usuario_id')->constrained('usuario');
            $table->timestamps();

            $table->index(['codigo_protegido', 'realizado']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('laboratorio');
    }
}
