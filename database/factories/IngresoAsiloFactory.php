<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Persona;
use App\Models\Usuario;
use App\Traits\Utileria;
use App\Models\Municipio;
use App\Models\Protegido;
use App\Models\TipoIngreso;
use App\Models\Departamento;
use App\Models\IngresoAsilo;
use Illuminate\Database\Eloquent\Factories\Factory;

class IngresoAsiloFactory extends Factory
{
    use Utileria;

    /**
     * The name of the factory"s corresponding model.
     *
     * @var string
     */
    protected $model = IngresoAsilo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $index = Protegido::count();
        $correlativo_anual = $this->generadorCodigo('ING', $index == 0 ? 1 : $index + 1, true);

        $tipo_ingreso = TipoIngreso::all()->random()->id;

        $fecha_ingreso = $this->faker->date('Y-m-d', 'now');
        $fecha_egreso = null;
        $deleted_at = null;
        $usuario = Usuario::all()->random()->id;

        if ($tipo_ingreso == 1) {
            $dias = $this->faker->numberBetween(31, 365);
            $fecha_egreso = date('Y-m-d', strtotime($fecha_ingreso . "+ {$dias} days"));

            $actual = Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
            $fecha_futuro = Carbon::createFromFormat('Y-m-d', $fecha_egreso);

            if ($actual->diffInDays($fecha_futuro) > 0)
                $deleted_at = date('Y-m-d H:i:s', strtotime($fecha_egreso));
        }

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

        $protegido = new Protegido();
        $protegido->codigo = $this->generadorCodigo('P', count(Protegido::all()) + 1);
        $protegido->activo = true;
        $protegido->persona_id = $persona->id;
        $protegido->usuario_id = $usuario;
        $protegido->save();

        $alazar = $this->faker->numberBetween(1, 1000);

        if (($alazar % 2) == 0) {
            $responsable = Persona::create(
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
        } else {
            $responsable = Persona::where('id', '!=', $persona->id)->get()->random();
        }

        return [
            "fecha_ingreso" => $fecha_ingreso,
            "fecha_egreso" => $fecha_egreso,
            "descuento" => $this->faker->numberBetween(0, 100),
            "protegido_id" => $protegido->id,
            "responsable_id" => $responsable->id,
            "tipo_ingreso_id" => $tipo_ingreso,
            "usuario_id" => $usuario,
            "correlativo_anual" => $correlativo_anual,
            "deleted_at" => $deleted_at
        ];
    }
}
