<?php

namespace App\Http\Controllers\V1\Farmacia;

use App\Models\Medicina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MedicinaPresentacion;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ApiController;

class MedicinaController extends ApiController
{
    private $controlador_principal = 'MedicinaController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(Medicina::with('precios.presentacion')->orderByDesc('id')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function store(Request $request)
    {
        try {
            $medicina = new Medicina();
            $medicina->codigo = $this->generadorCodigo('M-', count(Medicina::all()) + 1);
            $medicina->nombre = $request->nombre;
            $medicina->descripcion = $request->descripcion;
            $medicina->foto = $this->saveImage(!is_null($request->foto) ? $request->foto : "null", 'medicina', $medicina->codigo);
            $medicina->usuario_id = Auth::user()->id;
            $medicina->save();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse("Error en el controlador");
        }
    }

    public function update(Request $request, Medicina $medicina)
    {
        try {
            DB::beginTransaction();

            $medicina->foto = $this->saveImage(!is_null($request->foto) ? $request->foto : "null", 'medicina', $medicina->codigo, $medicina->foto);

            if ($medicina->nombre != $request->nombre) {
                foreach (MedicinaPresentacion::where('medicina_id', $medicina->id)->get() as $actualizar) {
                    $actualizar->nombre_medicina = $medicina->nombre;
                    $actualizar->save();
                }
            }

            $medicina->descripcion = $request->descripcion;
            $medicina->nombre = $request->nombre;
            $medicina->save();

            DB::commit();
            return $this->successResponse('Registro actualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@update");
            return $this->errorResponse('Error en el controlador');
        }
    }

    public function destroy(Medicina $medicina)
    {
        try {
            $medicina->delete();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            return $this->errorResponse('Error en el controlador');
        }
    }
}
