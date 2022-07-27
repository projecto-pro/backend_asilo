<?php

namespace Database\Factories;

use App\Models\Entidad;
use App\Models\Usuario;
use App\Models\Donacion;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Donacion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fecha_ingreso = $this->faker->date('Y-m-d', 'now');
        $texto = $this->faker->numberBetween(1, 3);

        return [
            "monto" => $this->faker->numberBetween(1, 500),
            "entidad_id" =>  Entidad::all()->random()->id,
            "fecha" =>  $fecha_ingreso,
            "anio" =>  date('Y', strtotime($fecha_ingreso)),
            "mes_id" =>  date('m', strtotime($fecha_ingreso)),
            "usuario_id" =>  Usuario::all()->random()->id
        ];
    }
}
