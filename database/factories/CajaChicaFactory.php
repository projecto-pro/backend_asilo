<?php

namespace Database\Factories;

use App\Models\CajaChica;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class CajaChicaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CajaChica::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $fecha_ingreso = $this->faker->date('Y-m-d H:i:s', 'now');
        $texto = $this->faker->numberBetween(1, 3);

        return [
            "monto" => $this->faker->numberBetween(1, 500),
            "descripcion" =>  $this->faker->sentence($texto, true),
            "fecha_hora" =>  $fecha_ingreso,
            "anio" =>  date('Y', strtotime($fecha_ingreso)),
            "mes_id" =>  date('m', strtotime($fecha_ingreso)),
            "usuario_id" =>  Usuario::all()->random()->id
        ];
    }
}
