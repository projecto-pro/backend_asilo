<?php

namespace App\Http\Controllers\V1\Catalogo;

use App\Models\Log;
use App\Models\Mes;
use App\Models\Examen;
use App\Models\Persona;
use App\Models\Municipio;
use App\Models\Protegido;
use App\Models\TipoIngreso;
use App\Models\Departamento;
use App\Models\Especialidad;
use App\Models\Presentacion;
use App\Models\ClienteProveedor;
use App\Models\MedicoEspecialidad;
use Illuminate\Support\Facades\DB;
use App\Models\MedicinaPresentacion;
use App\Http\Controllers\ApiController;
use App\Models\Antecedente;
use App\Models\Donacion;
use App\Models\Entidad;
use App\Models\Servicio;

class SelectController extends ApiController
{
    private $controlador_principal = 'SelectController';

    public function __construct()
    {
        parent::__construct();
    }

    public function log_select()
    {
        try {
            return $this->showAll(Log::orderByDesc('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@log_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function departamento_select()
    {
        try {
            return $this->showAll(Departamento::orderBy('nombre')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@departamento_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function municipio_select()
    {
        try {
            return $this->showAll(Municipio::orderBy('departamento_id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@municipio_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function presentacion_select()
    {
        try {
            return $this->showAll(Presentacion::orderBy('nombre')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@presentacion_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function persona_select()
    {
        try {
            return $this->showAll(Persona::with('municipio')->orderBy('cui')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@persona_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function mes_select()
    {
        try {
            return $this->showAll(Mes::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@mes_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function tipo_ingreso_select()
    {
        try {
            return $this->showAll(TipoIngreso::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@tipo_ingreso_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function protegido_select()
    {
        try {
            $data = Protegido::with('persona.municipio')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('ingreso_asilo')
                        ->whereRaw('protegido.id = ingreso_asilo.protegido_id')
                        ->whereNull('ingreso_asilo.deleted_at');
                })
                ->orderBy('id')
                ->get();
            return $this->showAll($data);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@protegido_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function especialidad_select()
    {
        try {
            return $this->showAll(Especialidad::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@especialidad_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function examen_select()
    {
        try {
            return $this->showAll(Examen::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@examen_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function medicina_select()
    {
        try {
            return $this->showAll(MedicinaPresentacion::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@medicina_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function medico_select()
    {
        try {
            $data = DB::table('medico_especialidad')
                ->join('medico', 'medico_especialidad.medico_id', 'medico.id')
                ->join('persona', 'medico.persona_id', 'persona.id')
                ->join('especialidad', 'medico_especialidad.especialidad_id', 'especialidad.id')
                ->select(
                    'medico.id as id',
                    DB::RAW("CONCAT(medico.colegiado,' - ',persona.primer_nombre,' ',persona.primer_apellido,', ',especialidad.nombre) as nombre")
                )
                ->where('medico.activo', true)
                ->orderBy('id')
                ->get();

            return $this->showAll($data);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@medico_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function ingreso_asilo_select()
    {
        try {
            $data = DB::table('ingreso_asilo')
                ->join('protegido', 'ingreso_asilo.protegido_id', 'protegido.id')
                ->join('persona', 'protegido.persona_id', 'persona.id')
                ->select(
                    'ingreso_asilo.id as id',
                    DB::RAW("CONCAT(ingreso_asilo.correlativo_anual,' | ', protegido.codigo,' - ',persona.primer_nombre,' ',persona.primer_apellido) as nombre")
                )
                ->whereNull('ingreso_asilo.deleted_at')
                ->orderBy('id')
                ->get();

            return $this->showAll($data);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@ingreso_asilo_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function antecedente_select()
    {
        try {
            return $this->showAll(Antecedente::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@antecedente_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function consulta_medica_select()
    {
        try {
            $data = DB::table('medico')
                ->join('persona', 'medico.persona_id', 'persona.id')
                ->select(
                    'medico.id as id',
                    DB::RAW("CONCAT(medico.colegiado,' - ',persona.primer_nombre,' ',persona.primer_apellido) as nombre")
                )
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('consulta_medica')
                        ->whereRaw('medico.id = consulta_medica.medico_id')
                        ->where('consulta_medica.atendido', false);
                })
                ->orderBy('id')
                ->get();

            return $this->showAll($data);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@consulta_medica_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function entrega_medicamento_select()
    {
        try {
            $data = Protegido::whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tratamiento')
                    ->whereRaw('protegido.id = tratamiento.protegido_id')
                    ->where('tratamiento.entregado', false);
            })
                ->orderBy('id')
                ->get();

            return $this->showAll($data);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@entrega_medicamento_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function donacion_select()
    {
        try {
            return $this->showAll(Donacion::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@donacion_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function entidad_select()
    {
        try {
            return $this->showAll(Entidad::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@entidad_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function servicio_select()
    {
        try {
            return $this->showAll(Servicio::orderBy('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@servicio_select");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function anios_select()
    {
        try {
            $anios = array();
            for ($i = 2000; $i < date('Y') + 1; $i++) {
                array_push($anios, $i);
            }
            return $this->successResponse(['data' => $anios]);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@anios_select");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
