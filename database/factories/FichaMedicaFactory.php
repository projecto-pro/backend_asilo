<?php

namespace Database\Factories;

use App\Models\Medico;
use App\Models\Persona;
use App\Models\Usuario;
use App\Traits\Utileria;
use App\Models\Municipio;
use App\Models\Protegido;
use App\Models\FichaMedica;
use App\Models\IngresoAsilo;
use App\Models\SolicitudMedica;
use Illuminate\Database\Eloquent\Factories\Factory;

class FichaMedicaFactory extends Factory
{
    /**
     * The name of the factory"s corresponding model.
     *
     * @var string
     */
    protected $model = FichaMedica::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        do {
            $protegido = Protegido::all()->random();
            $existe = FichaMedica::where('protegido_id', $protegido->id)->first();
        } while (!is_null($existe));

        $municipio = Municipio::find($protegido->persona->municipio_id);
        $solicitud_medica = SolicitudMedica::where('protegido_id', $protegido->id)->first();

        if (is_null($solicitud_medica)) {
            $ingreso_asilo = IngresoAsilo::whereNull('ingreso_asilo.deleted_at')->get()->random();
            $dias = $this->faker->numberBetween(1, 365);

            $fecha_actual = date('Y-m-d');
            $fecha = date('Y-m-d', strtotime($fecha_actual . "+ {$dias} days"));

            $texto = $this->faker->numberBetween(1, 25);

            $solicitud_medica = new SolicitudMedica();
            $solicitud_medica->fecha = $fecha;
            $solicitud_medica->motivo = $this->faker->sentence($texto, true);
            $solicitud_medica->ingreso_asilo_id = $ingreso_asilo->id;
            $solicitud_medica->protegido_id = $ingreso_asilo->protegido_id;
            $solicitud_medica->usuario_id = Usuario::all()->random()->id;
            $solicitud_medica->asignado = false;
            $solicitud_medica->save();
        }

        $contacto = Persona::where('id', '!=', $protegido->persona->id)->get()->random();

        $fecha_actual = date('Y-m-d');
        $menor = date('Y-m-d', strtotime($fecha_actual . "- 1 years"));
        $fecha_nacimiento = $this->faker->date('Y-m-d', $menor);

        $texto = $this->faker->numberBetween(1, 10);

        return [
            "fecha_ingreso" => date('Y-m-d'),
            "hora_ingreso" => date('H:i:s'),
            "fecha_nacimiento" => $fecha_nacimiento,
            "cui" => $protegido->persona->cui,
            "nombre_completo" => str_replace("  ", " ", "{$protegido->persona->primer_nombre} {$protegido->persona->segundo_nombre} {$protegido->persona->primer_apellido} {$protegido->persona->segundo_apellido}"),
            "genero" => $protegido->persona->genero,
            "direccion" => "{$municipio->getFullNameAttribute()}, {$protegido->persona->ubicacion}",
            "telefono" => $protegido->persona->telefono,
            "alergia" => $this->faker->sentence($texto, true),
            "protegido_id" => $protegido->id,
            "ingreso_asilo_id" => $solicitud_medica->ingreso_asilo_id,
            "contacto_id" => $contacto->id,
            "solicitud_medica_id" => $solicitud_medica->id,
            "medico_tratante_id" => Medico::all()->random()->id,
            "usuario_id" => Usuario::all()->random()->id,
        ];
    }
}
