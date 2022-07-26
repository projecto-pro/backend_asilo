<?php

namespace App\Http\Controllers\V1\Administracion;

use App\Models\Mensualidad;
use App\Models\IngresoAsilo;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class MensualidadController extends ApiController
{
    private $controlador_principal = 'MensualidadController';

    public function __construct()
    {
        parent::__construct();
    }

    public function edit(IngresoAsilo $mensualidad)
    {
        try {
            return $this->showAll(Mensualidad::with('mes', 'ingreso_asilo.protegido', 'usuario')->where('ingreso_asilo_id', $mensualidad->id)->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@show");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function show(Mensualidad $mensualidad)
    {
        try {
            $mensualidad->pagado = true;
            $mensualidad->fecha_pago = date('Y-m-d');
            $mensualidad->usuario_id = Auth::user()->id;
            $mensualidad->save();

            return $this->successResponse("Registro pagado");
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@show");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
