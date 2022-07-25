<?php

namespace Database\Factories;

use App\Models\Medico;
use App\Models\Especialidad;
use App\Models\MedicoEspecialidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicoEspecialidadFactory extends Factory
{
    /**
     * The name of the factory"s corresponding model.
     *
     * @var string
     */
    protected $model = MedicoEspecialidad::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $especialidad = Especialidad::all()->random()->id;
        $medico = Medico::all()->random()->id;

        while (!is_null(MedicoEspecialidad::where('medico_id', $medico)->where('especialidad_id', $especialidad)->first())) {
            $medico = Medico::all()->random()->id;
            $especialidad = Especialidad::all()->random()->id;
        }

        return [
            "medico_id" => $medico,
            "especialidad_id" => $especialidad
        ];
    }
}
