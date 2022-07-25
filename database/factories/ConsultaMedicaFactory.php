<?php

namespace Database\Factories;

use App\Models\ConsultaMedica;
use App\Models\FichaMedica;
use App\Models\Medico;
use App\Models\SolicitudMedica;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultaMedicaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsultaMedica::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do {
            $solicitud_medica = SolicitudMedica::all()->random();
        } while (is_null(FichaMedica::where('protegido_id', $solicitud_medica->protegido_id)->first()));

        $hora = $this->faker->numberBetween(6, 17);
        $solicitud_medica->asignado = true;
        $solicitud_medica->save();

        return [
            "fecha_hora" => $solicitud_medica->fecha,
            "hora" => "{$hora}:00",
            "subtotal" => 0,
            "descuento" => 0,
            "total" => 0,
            "pagado" => false,
            "solicitud_medica_id" => $solicitud_medica->id,
            "medico_id" => Medico::all()->random()->id,
            "usuario_id" => Usuario::all()->random()->id,
            "mes_id" => date('m', strtotime($solicitud_medica->fecha)),
            "atendido" => false,
            "anulado" => false
        ];
    }
}
