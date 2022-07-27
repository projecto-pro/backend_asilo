<?php

namespace App\Http\Controllers\V1\Administracion;

use App\Models\Entidad;
use App\Models\Donacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class DonacionController extends ApiController
{
    private $controlador_principal = 'DonacionController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(Donacion::with('mes', 'entidad')->orderByDesc('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $entidad = Entidad::where('nombre', $request->entidad)->first();
            if (is_null($entidad)) {
                $entidad = new Entidad();
                $entidad->nombre = $request->entidad;
                $entidad->save();
            }

            $data = new Donacion();
            $data->monto = $request->monto;
            $data->entidad_id = $entidad->id;
            $data->fecha = date('Y-m-d');
            $data->anio = date('Y');
            $data->mes_id = date('m');
            $data->usuario_id = Auth::user()->id;
            $data->save();

            DB::commit();
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(Donacion $donacion)
    {
        try {
            $donacion->delete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
