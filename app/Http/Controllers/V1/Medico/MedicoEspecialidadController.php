<?php

namespace App\Http\Controllers\V1\Medico;

use App\Models\Especialidad;
use Illuminate\Http\Request;
use App\Models\MedicoEspecialidad;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class MedicoEspecialidadController extends ApiController
{
    private $controlador_principal = 'MedicoEspecialidadController';

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $especialidad = Especialidad::where('nombre', $request->especialidad)->first();
            if (is_null($especialidad)) {
                $especialidad = new Especialidad();
                $especialidad->nombre = $request->especialidad;
                $especialidad->save();
            }

            if (!is_null(MedicoEspecialidad::where('medico_id', $request->id)->where('especialidad_id', $especialidad->id)->first())) {
                return $this->errorResponse('La especilidad ya fue asignada al médico.');
            }

            $asinar_especialidad = new MedicoEspecialidad();
            $asinar_especialidad->medico_id = $request->id;
            $asinar_especialidad->especialidad_id = $especialidad->id;
            $asinar_especialidad->save();

            DB::commit();
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(MedicoEspecialidad $asinar_especialidad)
    {
        try {
            $asinar_especialidad->delete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
