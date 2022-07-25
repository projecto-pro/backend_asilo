<?php

namespace Database\Factories;

use App\Models\FichaMedica;
use App\Models\ConsultaMedica;
use App\Models\EvolucionMedica;
use App\Models\HistorialMedico;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvolucionMedicaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EvolucionMedica::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $consulta_medica = ConsultaMedica::where('atendido', false)->get()->random();
        $ficha_historica = FichaMedica::where('protegido_id', $consulta_medica->solicitud_medica->protegido_id)->get()->random();

        $texto = $this->faker->numberBetween(1, 25);

        $historial_medico = new HistorialMedico();
        $historial_medico->correlativo = HistorialMedico::where('ficha_medica_id', $ficha_historica->id)->count() + 1;
        $historial_medico->concluida = $this->faker->randomElement([true, false]);
        $historial_medico->fecha_hora_inicio = date('Y-m-d H:i:s');
        $historial_medico->fecha_hora_finaliza = $historial_medico->concluida ? date('Y-m-d H:i:s') : null;
        $historial_medico->encargado = $this->faker->randomElement(["{$this->faker->firstNameMale} {$this->faker->lastName}", "{$this->faker->firstNameFemale} {$this->faker->lastName}", $ficha_historica->contacto->full_name]);
        $historial_medico->diagnostico = $this->faker->sentence($texto, true);
        $historial_medico->ficha_medica_id = $ficha_historica->id;
        $historial_medico->consulta_medica_id = $consulta_medica->id;
        $historial_medico->medico_id = $consulta_medica->medico_id;
        $historial_medico->save();

        $texto = $this->faker->numberBetween(1, 25);

        return [
            "correlativo" => 1,
            "fecha_hora" => $historial_medico->fecha_hora_inicio,
            "encargado" => $historial_medico->encargado,
            "evolucion" => $this->faker->sentence($texto, true),
            "historial_medico_id" => $historial_medico->id,
            "ficha_medica_id" => $historial_medico->ficha_medica_id,
            "consulta_medica_id" => $historial_medico->consulta_medica_id,
            "medico_id" => $historial_medico->medico_id
        ];
    }
}
