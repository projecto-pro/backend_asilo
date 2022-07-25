<?php

namespace App\Http\Controllers\V1\Rol;

use App\Models\Rol;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Illuminate\Database\QueryException;

class RolController extends ApiController
{
    private $controlador_principal = 'RolController';

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        try {
            return $this->showAll(Rol::with('roles_menus.menu')->get());
        } catch (\Exception $e) {
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@index");
            return $this->errorResponse($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $data = new Rol();
            $data->nombre = $request->nombre;
            $data->save();

            $this->asignar_menus($request->menus, $data->id);
            DB::commit();

            return $this->successResponse('Registro agregado.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@store");
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy(Rol $rol)
    {
        try {
            DB::beginTransaction();
            $rol->forceDelete();
            DB::commit();
            return $this->successResponse('Registro eliminado');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->grabarLog($e->getMessage(), "{$this->controlador_principal}@destroy");
            if ($e instanceof QueryException) {
                return $this->errorResponse('El registro se encuentra en uso', 423);
            }
        }
    }
}
