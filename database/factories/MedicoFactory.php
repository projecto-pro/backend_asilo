<?php

namespace Database\Factories;

use App\Models\Medico;
use App\Models\Persona;
use App\Models\Usuario;
use App\Models\Municipio;
use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicoFactory extends Factory
{
    /**
     * The name of the factory"s corresponding model.
     *
     * @var string
     */
    protected $model = Medico::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $persona = Persona::create(
            [
                "cui" => $this->faker->unique()->numerify("#############"),
                "primer_nombre" => $this->faker->randomElement([$this->faker->firstNameMale, $this->faker->firstNameFemale]),
                "segundo_nombre" => $this->faker->randomElement([$this->faker->firstNameMale, $this->faker->firstNameFemale, null]),
                "primer_apellido" => $this->faker->lastName,
                "segundo_apellido" => $this->faker->randomElement([$this->faker->lastName, null]),
                "email" => $this->faker->unique()->freeEmail,
                "ubicacion" => $this->faker->address,
                "telefono" => $this->faker->unique()->numerify("########"),
                "genero" => $this->faker->randomElement([Persona::GENERO[0], Persona::GENERO[1]]),
                "departamento_id" => Departamento::all()->random()->id,
                "municipio_id" => Municipio::all()->random()->id
            ]
        );

        return [
            "colegiado" => $this->faker->unique()->numerify("########"),
            "email" => $this->faker->unique()->freeEmail,
            "telefono" => $this->faker->unique()->numerify("########"),
            "activo" => $this->faker->randomElement([true, false]),
            "persona_id" => $persona->id
        ];
    }
}
