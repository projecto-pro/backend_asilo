<?php

namespace App\Http\Controllers\V1\Administracion;

use App\Models\CajaChica;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Auth;

class CajaChicaController extends ApiController
{
    private $controlador_principal = 'CajaChicaController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(CajaChica::with('mes')->orderByDesc('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            $data = new CajaChica();
            $data->monto = $request->monto;
            $data->descripcion = $request->descripcion;
            $data->fecha_hora = date('Y-m-d H:i:s');
            $data->anio = date('Y');
            $data->mes_id = date('m');
            $data->usuario_id = Auth::user()->id;
            $data->save();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function destroy(CajaChica $caja_chica)
    {
        try {
            $caja_chica->delete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
