<?php

use App\Models\Usuario;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.default'))->create('usuario', function (Blueprint $table) {
            $table->id();
            $table->string('cui', 15)->unique();
            $table->string('password');
            $table->string('admin')->default(Usuario::USUARIO_REGULAR);
            $table->enum('sistema', ['asilo', 'farmacia', 'laboratorio']);

            $table->foreignId('persona_id')->constrained('persona');

            $table->softDeletes();
            $table->timestamps();

            $table->index('admin');
            $table->index(['cui', 'password', 'deleted_at']);
            $table->index('sistema');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.default'))->dropIfExists('usuario');
    }
}
