<?php

namespace App\Http\Controllers\V1\Medico;

use App\Models\Medico;
use Illuminate\Http\Request;
use App\Models\MedicoEspecialidad;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class MedicoController extends ApiController
{
    private $controlador_principal = 'MedicoController';

    public function index()
    {
        try {
            return $this->showAll(Medico::with('persona.municipio', 'especialidades.especialidad')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $persona = $this->persona($request->persona);

            $medico = new Medico();
            $medico->colegiado = $request->colegiado;
            $medico->foto = $this->saveImage($request->foto, 'medico', $medico->colegiado);
            $medico->email = $request->email;
            $medico->telefono = $request->telefono;
            $medico->activo = true;
            $medico->persona_id = $persona->id;
            $medico->save();

            foreach ($request->especialidades as $especialidad) {
                $asinar_especialidad = new MedicoEspecialidad();
                $asinar_especialidad->medico_id = $medico->id;
                $asinar_especialidad->especialidad_id = $especialidad['id'];
                $asinar_especialidad->save();
            }

            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function update(Request $request, Medico $medico)
    {
        try {
            DB::beginTransaction();

            $persona = $this->persona($request->persona);

            if (!is_null($request->foto))
                $medico->foto = $this->saveImage($request->foto, 'medico', $medico->colegiado, $medico->foto);

            $medico->email = $request->email;
            $medico->telefono = $request->telefono;
            $medico->persona_id = $persona->id;
            $medico->save();

            DB::commit();
            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@update");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(Medico $medico)
    {
        try {

            $medico->activo = $medico->activo ? false : true;
            $medico->save();

            return $this->successResponse('Registro cambio de estado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
