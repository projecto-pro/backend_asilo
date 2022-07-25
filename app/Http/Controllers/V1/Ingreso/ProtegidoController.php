<?php

namespace App\Http\Controllers\V1\Ingreso;

use App\Models\Protegido;
use App\Http\Controllers\ApiController;

class ProtegidoController extends ApiController
{
    private $controlador_principal = 'ProtegidoController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(Protegido::with('persona.municipio')->orderByDesc('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@show");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function show(Protegido $protegido)
    {
        try {
            $protegido->activo = $protegido->activo ? false : true;
            $protegido->save();

            return $this->successResponse("Registro cambio de estado");
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@show");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
