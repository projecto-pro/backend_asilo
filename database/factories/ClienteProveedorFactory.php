<?php

namespace Database\Factories;

use App\Models\Municipio;
use App\Models\ClienteProveedor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClienteProveedorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClienteProveedor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $municipio = Municipio::all()->random();
        $direccion = $this->faker->randomElement([$this->faker->address, null]);
        return [
            'nit' => $this->faker->unique()->numerify('########'),
            'nombre' => $this->faker->randomElement([$this->faker->firstNameMale, $this->faker->firstNameFemale]),
            'telefonos' => $this->faker->randomElement([$this->faker->unique()->numerify('########'), null]),
            'emails' => $this->faker->randomElement([$this->faker->unique()->freeEmail, null]),
            'direcciones' => $direccion,
            'departamento_id' => $municipio->departamento_id,
            'municipio_id' => $municipio->id,
            'usuario_id' => 1
        ];
    }
}
