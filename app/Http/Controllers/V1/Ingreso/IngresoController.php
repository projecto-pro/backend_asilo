<?php

namespace App\Http\Controllers\V1\Ingreso;

use App\Models\Mes;
use App\Models\Mensualidad;
use App\Models\IngresoAsilo;
use Illuminate\Http\Request;
use App\Models\SolicitudMedica;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class IngresoController extends ApiController
{
    private $controlador_principal = 'IngresoController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->showAll(IngresoAsilo::todos()->get());
    }

    public function show(IngresoAsilo $ingreso_asilo)
    {
        try {
            $ingreso_asilo->delete();
            return $this->successResponse("Registro anulado");
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@show");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            return $this->successResponse($request);

            $protegido = $this->protegido($request->paciente);
            $responsable = $this->persona($request->responsable);

            if (!is_null(IngresoAsilo::where('protegido_id', $protegido->id)->whereNull('deleted_at')->first())) {
                DB::commit();

                return $this->errorResponse('El paciente que trata de registrar ya se encuentra registrado.');
            }

            $ingreso_asilo = new IngresoAsilo();
            $ingreso_asilo->fecha_ingreso = date('Y-m-d', strtotime($request->fecha_ingreso));
            $ingreso_asilo->fecha_egreso = $request->tipo_ingreso_id['id'] == 1 ? date('Y-m-d', strtotime($request->fecha_egreso)) : null;
            $ingreso_asilo->descuento = $request->descuento;
            $ingreso_asilo->protegido_id = $protegido->id;
            $ingreso_asilo->responsable_id = $responsable->id;
            $ingreso_asilo->tipo_ingreso_id = $request->tipo_ingreso_id['id'];
            $ingreso_asilo->usuario_id = Auth::user()->id;
            $ingreso_asilo->correlativo_anual = $this->generadorCodigo('ING', IngresoAsilo::withTrashed()->count() + 1, true);
            $ingreso_asilo->save();

            $anio = date('Y');
            foreach (Mes::orderBy('id')->get() as $mes) {
                $this->mesualidad($mes->id, $ingreso_asilo->id, $anio);
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(IngresoAsilo $ingreso_asilo)
    {
        try {
            DB::beginTransaction();

            $existe = SolicitudMedica::where('ingreso_asilo_id', $ingreso_asilo->id)->first();

            if (!is_null($existe)) {
                return $this->successResponse("El registro ya cuenta con una solicitud médica, no se puede eliminar.");
            }

            $existe = Mensualidad::where('ingreso_asilo_id', $ingreso_asilo->id)->where('pagado', true)->first();

            if (!is_null($existe)) {
                return $this->successResponse("El registro ya cuenta con una mensulidad cancelada, no se puede eliminar.");
            }

            Mensualidad::where('ingreso_asilo_id', $ingreso_asilo->id)->delete();
            $ingreso_asilo->forceDelete();

            DB::commit();

            return $this->successResponse("Registro eliminado");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }
}
