<?php

namespace Database\Factories;

use App\Models\Usuario;
use App\Models\IngresoAsilo;
use App\Models\SolicitudMedica;
use Illuminate\Database\Eloquent\Factories\Factory;

class SolicitudMedicaFactory extends Factory
{
    /**
     * The name of the factory"s corresponding model.
     *
     * @var string
     */
    protected $model = SolicitudMedica::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $ingreso_asilo = IngresoAsilo::whereNull('ingreso_asilo.deleted_at')->get()->random();
        $dias = $this->faker->numberBetween(1, 365);

        $fecha_actual = date('Y-m-d');
        $fecha = date('Y-m-d', strtotime($fecha_actual . "+ {$dias} days"));

        $texto = $this->faker->numberBetween(1, 25);

        return [
            "fecha" => $fecha,
            "motivo" => $this->faker->sentence($texto, true),
            "ingreso_asilo_id" => $ingreso_asilo->id,
            "protegido_id" => $ingreso_asilo->protegido_id,
            "usuario_id" => Usuario::all()->random()->id,
            "asignado" => false
        ];
    }
}
