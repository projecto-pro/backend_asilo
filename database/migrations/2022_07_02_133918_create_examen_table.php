<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('examen', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->decimal('precio', 10, 2);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nombre', 'deleted_at']);
            $table->index('deleted_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('examen');
    }
}
