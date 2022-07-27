<?php

namespace Database\Factories;

use App\Models\Mes;
use App\Models\Usuario;
use App\Models\Servicio;
use App\Models\PagoServicio;
use Illuminate\Database\Eloquent\Factories\Factory;

class PagoServicioFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PagoServicio::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do {
            $anio = $this->faker->numberBetween(2000, date('Y'));
            $mes_id = Mes::all()->random()->id;
            $dia = $this->faker->numberBetween(1, $mes_id = 2 ? 28 : 30);
            $servicio = Servicio::all()->random()->id;
        } while (!is_null(PagoServicio::where('servicio_id', $servicio)->where('mes_id', $mes_id)->where('anio', $anio)->first()));

        return [
            "monto" => $this->faker->numberBetween(1, 500),
            "servicio_id" =>  $servicio,
            "fecha" =>  "{$anio}-{$mes_id}-{$dia}",
            "anio" =>  $anio,
            "mes_id" =>  $mes_id,
            "usuario_id" =>  Usuario::all()->random()->id
        ];
    }
}
