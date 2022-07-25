<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMedicinaPresentacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('medicina_presentacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_medicina', 100);
            $table->string('nombre_presentacion', 20);
            $table->decimal('precio', 10, 2);

            $table->foreignId('medicina_id')->constrained('medicina');
            $table->foreignId('presentacion_id')->constrained('presentacion');
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
        Schema::connection(config('database.default'))->dropIfExists('medicina_presentacion');
    }
}
