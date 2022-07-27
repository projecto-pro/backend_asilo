<?php

namespace Database\Seeders;

use App\Models\Mes;
use App\Models\Examen;
use App\Models\Medico;
use App\Models\Usuario;
use App\Traits\Utileria;
use App\Models\UsuarioRol;
use App\Models\Antecedente;
use App\Models\FichaMedica;
use App\Models\Laboratorio;
use App\Models\Tratamiento;
use App\Models\IngresoAsilo;
use App\Imports\ExamenImport;
use App\Imports\RolMenuImport;
use App\Models\ConsultaMedica;
use App\Imports\MedicinaImport;
use App\Models\EvolucionMedica;
use App\Models\ExamenRealizado;
use App\Models\SolicitudMedica;
use App\Traits\ConsultasGlobal;
use Illuminate\Database\Seeder;
use App\Imports\MunicipioImport;
use App\Models\MedicoEspecialidad;
use Illuminate\Support\Facades\DB;
use App\Imports\DepartamentoImport;
use App\Models\CajaChica;
use App\Models\Donacion;
use App\Models\Entidad;
use App\Models\MedicinaPresentacion;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FichaMedicaAntecedente;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use ConsultasGlobal, Utileria;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Migrando data para los roles, menus y asociando menu al rol correspondiente
        Excel::import(new RolMenuImport, 'database/seeders/Catalogos/RolMenu.xlsx');

        //Migrando Departamento y Municipios asociados
        Excel::import(new DepartamentoImport, 'database/seeders/Catalogos/Departamentos.xlsx');
        Excel::import(new MunicipioImport, 'database/seeders/Catalogos/Municipios.xlsx');
        Excel::import(new ExamenImport, 'database/seeders/Catalogos/Laboratorio.xlsx');

        Usuario::factory(7)->create();

        $usuario = Usuario::where('cui', '0000000000001')->first();
        UsuarioRol::create([
            'usuario_id' => $usuario->id,
            'rol_id' => 1
        ]);

        Artisan::call('passport:install');

        DB::connection(config('passport.storage.database.connection'))->table('oauth_clients')
            ->where('id', 2)
            ->update(['secret' => 'fb8QYHjGOBKEL4S8CMDNkXW44lSHGmM6lrvoxwO1']);

        Excel::import(new MedicinaImport, 'database/seeders/Catalogos/medicamentos.xlsx');

        Medico::factory(25)->create();
        echo "Ejecutado Medico::factory()->create()" . PHP_EOL;

        MedicoEspecialidad::factory(75)->create();
        echo "Ejecutado MedicoEspecialidad::factory()->create()" . PHP_EOL;

        IngresoAsilo::factory(250)->create();
        echo "Ejecutado IngresoAsilo::factory()->create()" . PHP_EOL;

        $anio = date('Y');
        foreach (IngresoAsilo::all() as $value) {
            foreach (Mes::orderBy('id')->get() as $mes) {
                $simular_pago = random_int(0, 1);
                $this->mesualidad($mes->id, $value->id, $anio, $simular_pago == 1, Usuario::all()->random()->id);
            }
        }
        echo "Ejecutado foreach (IngresoAsilo::all() as value)" . PHP_EOL;

        SolicitudMedica::factory(250)->create();
        echo "Ejecutado SolicitudMedica::factory()->create()" . PHP_EOL;

        for ($i = 0; $i < 100; $i++) {
            $expediente = $this->generadorCodigo("EXP", FichaMedica::count() + 1, true);
            FichaMedica::factory()->create(
                [
                    "expediente" =>  $expediente
                ]
            );
        }
        echo "Ejecutado FichaMedica::factory()->create()" . PHP_EOL;
        //FichaMedicaAntecedente::factory(500)->create();

        foreach (FichaMedica::all() as $value) {
            $antecedes = random_int(1, Antecedente::count());
            for ($i = 0; $i < $antecedes; $i++) {
                $ficha_medica_antecedente = new FichaMedicaAntecedente();
                $ficha_medica_antecedente->ficha_medica_id = $value->id;
                $ficha_medica_antecedente->antecedente_id = Antecedente::find($i + 1)->id;
                $ficha_medica_antecedente->save();
            }
        }
        echo "Ejecutado foreach (FichaMedica::all() as value)" . PHP_EOL;

        ConsultaMedica::factory(75)->create();
        echo "Ejecutado ConsultaMedica::factory()->create()" . PHP_EOL;

        EvolucionMedica::factory(200)->create();
        echo "Ejecutado EvolucionMedica::factory()->create()" . PHP_EOL;

        foreach (EvolucionMedica::all() as $evolucion_medica) {
            $tratamientos = random_int(1, 5);

            for ($i = 0; $i < $tratamientos; $i++) {
                $tratamiento = MedicinaPresentacion::all()->random();
                $horas = random_int(1, 24);

                $simular_cancelado = random_int(0, 1);
                $simular_entregado = random_int(0, 1);

                $recetar = new Tratamiento();
                $recetar->codigo_protegido = $evolucion_medica->ficha_medica->protegido->codigo;
                $recetar->cantidad = 1;
                $recetar->medicamento = "{$tratamiento->nombre_presentacion} - {$tratamiento->nombre_medicina}";
                $recetar->indicacion = $horas == 1 ? "Tomar 1 {$tratamiento->nombre_presentacion} cada {$horas} hora" : "Tomar 1 {$tratamiento->nombre_presentacion} cada {$horas} horas";
                $recetar->precio = $tratamiento->precio;
                $recetar->subtotal = $recetar->cantidad * $recetar->precio;
                $recetar->descuento = $recetar->precio - (($recetar->precio * $evolucion_medica->ficha_medica->solicitud_medica->ingreso_asilo->descuento) / 100);
                $recetar->descuento_aplicado = $recetar->cantidad * $recetar->descuento;
                $recetar->total = $recetar->subtotal - $recetar->descuento_aplicado;
                $recetar->cancelado = $simular_cancelado == 1;
                $recetar->entregado = $simular_entregado == 1;
                $recetar->protegido_id = $evolucion_medica->ficha_medica->protegido_id;
                $recetar->evolucion_medica_id = $evolucion_medica->id;
                $recetar->medicina_presentacion_id = $tratamiento->id;
                $recetar->usuario_id = Usuario::all()->random()->id;
                $recetar->save();

                if ($recetar->entregado) {
                    $this->agregarCobroFactura($recetar->evolucion_medica_id, $recetar->subtotal, $recetar->descuento_aplicado);
                }

                if ($recetar->cancelado) {
                    $this->verificarPagoConsultaCompleta($recetar->evolucion_medica_id);
                }
            }
        }
        echo "Ejecutado foreach (EvolucionMedica::all() as evolucion_medica)" . PHP_EOL;

        foreach (EvolucionMedica::all() as $evolucion_medica) {
            $laboratorios = random_int(1, 2);

            for ($i = 0; $i < $laboratorios; $i++) {
                $laboratorio = Examen::all()->random();

                $simular_cancelado = random_int(0, 1);
                $simular_realizado = random_int(0, 1);

                $realizar = new Laboratorio();
                $realizar->codigo_protegido = $evolucion_medica->ficha_medica->protegido->codigo;
                $realizar->examen = $laboratorio->nombre;
                $realizar->precio = $laboratorio->precio;
                $realizar->subtotal = $realizar->precio;
                $realizar->descuento = $realizar->precio - (($realizar->precio * $evolucion_medica->ficha_medica->solicitud_medica->ingreso_asilo->descuento) / 100);
                $realizar->descuento_aplicado = $realizar->descuento;
                $realizar->total = $realizar->subtotal - $realizar->descuento_aplicado;
                $realizar->cancelado = $simular_cancelado == 1;
                $realizar->realizado = $simular_realizado == 1;
                $realizar->protegido_id = $evolucion_medica->ficha_medica->protegido->id;
                $realizar->evolucion_medica_id = $evolucion_medica->id;
                $realizar->examen_id = $laboratorio->id;
                $realizar->usuario_id = Usuario::all()->random()->id;
                $realizar->save();

                if ($realizar->realizado) {
                    $this->agregarCobroFactura($realizar->evolucion_medica_id, $realizar->subtotal, $realizar->descuento_aplicado);

                    $examen_realizado = new ExamenRealizado();
                    $examen_realizado->examen = date('Y-m-d H:i:s');
                    $examen_realizado->documento = null;
                    $examen_realizado->laboratorio_id = $realizar->id;
                    $examen_realizado->usuario_id = Usuario::all()->random()->id;
                    $examen_realizado->save();
                }

                if ($realizar->cancelado) {
                    $this->verificarPagoConsultaCompleta($realizar->evolucion_medica_id);
                }
            }
        }
        echo "Ejecutado foreach (EvolucionMedica::all() as evolucion_medica)" . PHP_EOL;

        CajaChica::factory(1500)->create();
        echo "Ejecutado CajaChica::factory()->create()" . PHP_EOL;

        Entidad::factory(25)->create();
        echo "Ejecutado Entidad::factory()->create()" . PHP_EOL;

        Donacion::factory(2500)->create();
        echo "Ejecutado Donacion::factory()->create()" . PHP_EOL;
    }
}
