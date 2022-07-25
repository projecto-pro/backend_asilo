<?php

namespace Database\Factories;

use App\Models\Antecedente;
use App\Models\FichaMedica;
use App\Models\FichaMedicaAntecedente;
use Illuminate\Database\Eloquent\Factories\Factory;

class FichaMedicaAntecedenteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FichaMedicaAntecedente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do {
            $ficha_medica_id = FichaMedica::all()->random()->id;
            $antecedente_id = Antecedente::all()->random()->id;
        } while (!is_null(FichaMedicaAntecedente::where('ficha_medica_id', $ficha_medica_id)->where('antecedente_id', $antecedente_id)->first()));

        return [
            "ficha_medica_id" => $ficha_medica_id,
            "antecedente_id" => $antecedente_id
        ];
    }
}
