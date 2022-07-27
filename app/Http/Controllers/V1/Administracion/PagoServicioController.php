<?php

namespace App\Http\Controllers\V1\Administracion;

use App\Models\Mes;
use App\Models\PagoServicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class PagoServicioController extends ApiController
{
    private $controlador_principal = 'PagoServicioController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(PagoServicio::with('mes', 'servicio')->orderByDesc('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function show($pago_servicio)
    {
        try {
            $data = Mes::whereNotExists(function ($query) use ($pago_servicio) {
                $query->select(DB::raw(1))
                    ->from('pago_servicio')
                    ->whereRaw('mes.id = pago_servicio.mes_id')
                    ->where('pago_servicio.anio', $pago_servicio);
            })
                ->orderBy('id')
                ->get();

            return $this->showAll($data);
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function store(Request $request)
    {
        try {
            $data = new PagoServicio();
            $data->monto = $request->monto;
            $data->servicio_id = $request->servicio_id;
            $data->fecha = date('Y-m-d');
            $data->anio = $request->anio;
            $data->mes_id = $request->mes_id;
            $data->usuario_id = Auth::user()->id;
            $data->save();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(PagoServicio $pago_servicio)
    {
        try {
            $pago_servicio->delete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
