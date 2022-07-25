<?php

namespace App\Http\Controllers\V1\Catalogo;

use App\Models\Presentacion;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class PresentacionController extends ApiController
{
    private $controlador_principal = 'PresentacionController';

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        try {
            $data = new Presentacion();
            $data->nombre = $request->nombre;
            $data->save();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function destroy(Presentacion $presentacion)
    {
        try {
            $presentacion->forceDelete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }
}
