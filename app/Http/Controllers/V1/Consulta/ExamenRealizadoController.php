<?php

namespace App\Http\Controllers\V1\Consulta;

use App\Models\Laboratorio;
use Illuminate\Http\Request;
use App\Models\ExamenRealizado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class ExamenRealizadoController extends ApiController
{
    private $controlador_principal = 'ExamenRealizadoController';

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $laboratorio = Laboratorio::find($request->laboratorio_id);

            $examen_realizado = new ExamenRealizado();
            $examen_realizado->examen = date('Y-m-d H:i:s');
            $examen_realizado->documento = $request->documento;
            $examen_realizado->laboratorio_id = $laboratorio->id;
            $examen_realizado->usuario_id = Auth::user()->id;
            $examen_realizado->save();

            DB::commit();

            return $this->successResponse('Registro agregado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
