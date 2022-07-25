<?php

namespace App\Http\Controllers\V1\Farmacia;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MedicinaPresentacion;
use App\Http\Controllers\ApiController;
use App\Models\Medicina;
use App\Models\Presentacion;

class MedicinaPresentacionController extends ApiController
{
    private $controlador_principal = 'MedicinaPresentacionController';

    public function __construct()
    {
        parent::__construct();
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $medicina = Medicina::find($request->id);

            $presentacion = Presentacion::where('nombre', $request->presentacion)->first();
            if (is_null($presentacion)) {
                $presentacion = new Presentacion();
                $presentacion->nombre = $request->presentacion;
                $presentacion->save();
            }

            if (!is_null(MedicinaPresentacion::where('medicina_id', $medicina->id)->where('presentacion_id', $presentacion->id)->first())) {
                return $this->errorResponse('El precio ya fue registrado para la presentación.');
            }

            $medicina_presentacion = new MedicinaPresentacion();
            $medicina_presentacion->nombre_medicina = $medicina->nombre;
            $medicina_presentacion->nombre_presentacion = $presentacion->nombre;
            $medicina_presentacion->precio = $request->precio;
            $medicina_presentacion->medicina_id = $medicina->id;
            $medicina_presentacion->presentacion_id = $presentacion->id;
            $medicina_presentacion->save();

            DB::commit();
            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function update(Request $request, MedicinaPresentacion $medicina_presentacion)
    {
        try {
            $medicina_presentacion->precio = $request->precio;
            $medicina_presentacion->save();

            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@update");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
