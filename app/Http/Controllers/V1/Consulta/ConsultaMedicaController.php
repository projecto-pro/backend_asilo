<?php

namespace App\Http\Controllers\V1\Consulta;

use App\Models\Medico;
use Illuminate\Http\Request;
use App\Models\ConsultaMedica;
use App\Models\SolicitudMedica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class ConsultaMedicaController extends ApiController
{
    private $controlador_principal = 'ConsultaMedicaController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        try {
            $data = ConsultaMedica::with('solicitud_medica.protegido.persona', 'medico')
                ->when(isset($request->atendido), function ($query, $request) {
                    $query->where('atendido', $request->atendido)->where('anulado', false);
                })
                ->get();
            return $this->showAll($data);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function show(Medico $consulta_medica)
    {
        try {
            return $this->showAll(ConsultaMedica::with('solicitud_medica.protegido.persona.municipio', 'medico')->where('atendido', false)->where('medico_id', $consulta_medica->id)->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $solicitud_medica = SolicitudMedica::find($request->id);

            $consulta_medica = new ConsultaMedica();
            $consulta_medica->fecha_hora = $solicitud_medica->fecha;
            $consulta_medica->hora = $request->hora;
            $consulta_medica->subtotal = 0;
            $consulta_medica->descuento = 0;
            $consulta_medica->total = 0;
            $consulta_medica->pagado = false;
            $consulta_medica->solicitud_medica_id = $solicitud_medica->id;
            $consulta_medica->medico_id = $request->medico_id;
            $consulta_medica->usuario_id = Auth::user()->id;
            $consulta_medica->mes_id = date('m', strtotime($consulta_medica->fecha_hora));
            $consulta_medica->atendido = false;
            $consulta_medica->anulado = false;
            $consulta_medica->save();

            $solicitud_medica->asignado = true;
            $solicitud_medica->save();

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(ConsultaMedica $consulta_medica)
    {
        try {
            DB::beginTransaction();

            $solicitud_medica = SolicitudMedica::find($consulta_medica->solicitud_medica_id);
            $solicitud_medica->asignado = false;
            $solicitud_medica->save();

            $consulta_medica->anulado = true;
            $consulta_medica->save();

            DB::commit();

            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function atendido(ConsultaMedica $consulta_medica)
    {
        try {
            $consulta_medica->atendido = true;
            $consulta_medica->save();

            return $this->successResponse('Registro cambio de estado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@atendido");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
