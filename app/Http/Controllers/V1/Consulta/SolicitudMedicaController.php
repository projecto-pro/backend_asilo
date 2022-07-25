<?php

namespace App\Http\Controllers\V1\Consulta;

use App\Models\Protegido;
use App\Models\IngresoAsilo;
use Illuminate\Http\Request;
use App\Models\ConsultaMedica;
use App\Models\SolicitudMedica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class SolicitudMedicaController extends ApiController
{
    private $controlador_principal = 'SolicitudMedicaController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(SolicitudMedica::with('protegido.persona.municipio', 'ingreso_asilo')->where('asignado', false)->orderByDesc('solicitud_medica.id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function show(Protegido $solicitud_medica)
    {
        try {
            return $this->showAll(SolicitudMedica::where('protegido_id', $solicitud_medica->id)->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@show");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {

            $ingreso_asilo = IngresoAsilo::find($request->id);

            $solicitud_medica = new SolicitudMedica();
            $solicitud_medica->fecha = date('Y-m-d', strtotime($request->fecha));
            $solicitud_medica->motivo = $request->motivo;
            $solicitud_medica->ingreso_asilo_id = $ingreso_asilo->id;
            $solicitud_medica->protegido_id = $ingreso_asilo->protegido_id;
            $solicitud_medica->usuario_id = Auth::user()->id;
            $solicitud_medica->asignado = false;
            $solicitud_medica->save();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(SolicitudMedica $solicitud_medica)
    {
        try {
            DB::beginTransaction();

            ConsultaMedica::where('solicitud_medica_id', $solicitud_medica->id)->where('anulado', true)->delete();
            $solicitud_medica->delete();

            DB::commit();

            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }
}
